<?php
namespace In2code\Ipandlanguageredirect\Domain\Service;

use In2code\Ipandlanguageredirect\Domain\Model\ActionSet;
use In2code\Ipandlanguageredirect\Domain\Model\Configuration;
use In2code\Ipandlanguageredirect\Domain\Model\ConfigurationSet;
use In2code\Ipandlanguageredirect\Utility\ConfigurationUtility;
use In2code\Ipandlanguageredirect\Utility\FrontendUtility;
use In2code\Ipandlanguageredirect\Utility\IpUtility;
use In2code\Ipandlanguageredirect\Utility\ObjectUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Class RedirectService
 */
class RedirectService
{

    /**
     * Redirect configuration
     *
     * @var array
     */
    protected $configuration = [];

    /**
     * @var string
     */
    protected $browserLanguage = '';

    /**
     * @var string
     */
    protected $referrer = '';

    /**
     * @var string
     */
    protected $ipAddress = '';

    /**
     * @var string
     */
    protected $countryCode = '';

    /**
     * @var string
     */
    protected $countryCodeOverlay = '';

    /**
     * @var string
     */
    protected $domain = '';

    /**
     * @var string
     */
    protected $languageParameter = 'L';

    /**
     * @var int
     */
    protected $languageUid = 0;

    /**
     * @var int
     */
    protected $rootpageUid = 1;

    /**
     * Enables/Disables function for JavaScript
     *
     * @var bool
     */
    protected $activated = true;

    /**
     * @var null|Configuration
     */
    protected $bestConfiguration = null;

    /**
     * @var null|array
     */
    protected $bestEvents = null;

    /**
     * @var bool
     */
    protected $isActivatedBecauseOfDifferentLanguages = false;

    /**
     * @var bool
     */
    protected $isActivatedBecauseOfDifferentRootpages = false;

    /**
     * @var array
     */
    protected $defaultParameters = [
        'activated' => false,
        'events' => ['none']
    ];

    /**
     * @param string $browserLanguage
     * @param string $referrer
     * @param string $ipAddress
     * @param int $languageUid current FE language uid
     * @param int $rootpageUid current rootpage uid
     * @param string $countryCode
     * @param string $domain
     */
    public function __construct(
        string $browserLanguage,
        string $referrer,
        string $ipAddress,
        int $languageUid,
        int $rootpageUid,
        string $countryCode,
        string $domain
    ) {
        $this->browserLanguage = $browserLanguage;
        $this->referrer = $referrer;
        $this->ipAddress = $ipAddress;
        $this->languageUid = $languageUid;
        $this->rootpageUid = $rootpageUid;
        $this->countryCodeOverlay = $countryCode;
        if ($this->countryCodeOverlay === '') {
            $ipToCountry = ObjectUtility::getObjectManager()->get(IpToCountry::class);
            $this->countryCode = $ipToCountry->getCountryFromIp($ipAddress);
        } else {
            $this->countryCode = $this->countryCodeOverlay;
        }
        if ($domain === '') {
            $this->domain = GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY');
        } else {
            $this->domain = $domain;
        }
        $this->configuration = ConfigurationUtility::getRedirectConfiguration();
    }

    /**
     * @return array
     */
    public function buildParameters()
    {
        $redirectUri = $this->getRedirectUri();
        $parameters = $this->defaultParameters;
        if (!empty($redirectUri)) {
            $parameters = [
                'redirectUri' => $redirectUri,
                'activated' => $this->isActivated(),
                'events' => $this->getEvents(),
                'activatedReasons' => [
                    'differentLanguages' => $this->isActivatedBecauseOfDifferentLanguages,
                    'differentRootpages' => $this->isActivatedBecauseOfDifferentRootpages,
                ],
                'country' => $this->countryCode,
                'givenParameters' => [
                    'browserLanguage' => $this->browserLanguage,
                    'referrer' => $this->referrer,
                    'ipAddress' => $this->ipAddress,
                    'languageUid' => $this->languageUid,
                    'rootpageUid' => $this->rootpageUid,
                    'countryCodeOverlay' => $this->countryCodeOverlay,
                    'domain' => $this->domain
                ]
            ];
        }
        return $parameters;
    }

    /**
     * @return string
     */
    protected function getRedirectUri()
    {
        return $this->getUriToPageAndLanguage(
            $this->getBestMatchingRootPage(),
            $this->getBestMatchingLanguageParameter()
        );
    }

    /**
     * @return int
     */
    protected function getBestMatchingRootPage()
    {
        $bestConfiguration = $this->getBestConfiguration();
        if ($bestConfiguration !== null) {
            return $this->getBestConfiguration()->getRootPage();
        }
        return 1;
    }

    /**
     * @return int
     */
    protected function getBestMatchingLanguageParameter()
    {
        $bestConfiguration = $this->getBestConfiguration();
        if ($bestConfiguration !== null) {
            return $this->getBestConfiguration()->getLanguageParameter();
        }
        return 0;
    }

    /**
     * @return Configuration|null
     */
    protected function getBestConfiguration()
    {
        if ($this->bestConfiguration === null) {
            $configurationSet = ObjectUtility::getObjectManager()->get(ConfigurationSet::class, $this->configuration);
            $configurationSet->calculateQuantifiers($this->browserLanguage, $this->countryCode, $this->domain);
            $bestConfiguration = $configurationSet->getBestFittingConfiguration();
            $this->bestConfiguration = $bestConfiguration;
            if ($bestConfiguration === null) {
                $this->setDeactivated();
            }
        } else {
            $bestConfiguration = $this->bestConfiguration;
        }
        return $bestConfiguration;
    }

    /**
     * @param int $pageIdentifier
     * @param int $languageParameter
     * @return string
     */
    protected function getUriToPageAndLanguage($pageIdentifier = 0, $languageParameter = 0): string
    {
        $uriBuilder = ObjectUtility::getObjectManager()->get(UriBuilder::class);
        $uriBuilder->setTargetPageUid($pageIdentifier);
        $uriBuilder->setCreateAbsoluteUri(true);
        $uriBuilder->setArguments([$this->languageParameter => $languageParameter]);
        return $uriBuilder->buildFrontendUri();
    }

    /**
     * @return array|null
     */
    protected function getEvents()
    {
        if ($this->bestEvents === null) {
            $actionSet = ObjectUtility::getObjectManager()->get(ActionSet::class, $this->configuration);
            $actionSet->calculateQuantifiers($this->referrer);
            $events = $actionSet->getEvents();
        } else {
            $events = $this->bestEvents;
        }
        return $events;
    }

    /**
     * Check if
     *      - the redirect is turned on,
     *      - AND
     *          - if the current language is different to best matching language
     *          - OR if current rootpage is different to best matching rootpage
     *      - AND if event handling is not turned off
     *      - AND if actionOnHomeOnly is fullfilled
     *
     * @return boolean
     */
    protected function isActivated(): bool
    {
        return $this->activated && $this->isActivationNeeded() && $this->isEventhandlingDisabled() === false
            && $this->isActionOnHomeOnlyFullfilled();
    }

    /**
     * @return bool
     */
    protected function isActivatedBecauseOfDifferentLanguages(): bool
    {
        $isDifferent = $this->getBestMatchingLanguageParameter() !== $this->languageUid;
        $this->isActivatedBecauseOfDifferentLanguages = $isDifferent;
        return $isDifferent;
    }

    /**
     * @return bool
     */
    protected function isActivatedBecauseOfDifferentRootpages(): bool
    {
        $isDifferent = $this->getBestMatchingRootPage() !== $this->rootpageUid;
        $this->isActivatedBecauseOfDifferentRootpages = $isDifferent;
        return $isDifferent;
    }

    /**
     * @return bool
     */
    protected function isEventhandlingDisabled(): bool
    {
        $events = $this->getEvents();
        return !empty($events) && $events[0] === 'none';
    }

    /**
     * @return bool
     */
    protected function isActivationNeeded(): bool
    {
        return $this->isActivatedBecauseOfDifferentLanguages() || $this->isActivatedBecauseOfDifferentRootpages();
    }

    /**
     * Check, if actionOnHomeOnly is turned on, current page is a home page
     *
     * @return bool
     */
    protected function isActionOnHomeOnlyFullfilled(): bool
    {
        if (!empty($this->configuration['globalConfiguration']['actionOnHomeOnly'])
            && $this->configuration['globalConfiguration']['actionOnHomeOnly'] === true) {
            return $this->isCurrentPageAHomePage();
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function isCurrentPageAHomePage(): bool
    {
        return $this->rootpageUid === FrontendUtility::getCurrentPageIdentifier();
    }

    /**
     * @return RedirectService
     */
    protected function setDeactivated(): RedirectService
    {
        $this->activated = false;
        return $this;
    }
}
