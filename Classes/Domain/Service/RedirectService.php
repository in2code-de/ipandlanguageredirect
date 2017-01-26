<?php
namespace In2code\Ipandlanguageredirect\Domain\Service;

use In2code\Ipandlanguageredirect\Domain\Model\ActionSet;
use In2code\Ipandlanguageredirect\Domain\Model\Configuration;
use In2code\Ipandlanguageredirect\Domain\Model\ConfigurationSet;
use In2code\Ipandlanguageredirect\Utility\ConfigurationUtility;
use In2code\Ipandlanguageredirect\Utility\IpUtility;
use In2code\Ipandlanguageredirect\Utility\ObjectUtility;
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
     * RedirectService constructor.
     * @param string $browserLanguage
     * @param string $referrer
     * @param string $ipAddress
     * @param int $languageUid current FE language uid
     * @param int $rootpageUid current rootpage uid
     * @param string $countryCode
     */
    public function __construct($browserLanguage, $referrer, $ipAddress, $languageUid, $rootpageUid, $countryCode)
    {
        $this->browserLanguage = $browserLanguage;
        $this->referrer = $referrer;
        $this->ipAddress = $ipAddress;
        $this->languageUid = $languageUid;
        $this->rootpageUid = $rootpageUid;
        $this->countryCodeOverlay = $countryCode;
        if ($this->countryCodeOverlay === '') {
            $this->countryCode = IpUtility::getCountryCodeFromIp($ipAddress);
        } else {
            $this->countryCode = $this->countryCodeOverlay;
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
                'givenParameters' => [
                    'browserLanguage' => $this->browserLanguage,
                    'referrer' => $this->referrer,
                    'ipAddress' => $this->ipAddress,
                    'languageUid' => $this->languageUid,
                    'rootpageUid' => $this->rootpageUid,
                    'countryCodeOverlay' => $this->countryCodeOverlay
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
            $configurationSet->calculateQuantifiers($this->browserLanguage, $this->countryCode);
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
    protected function getUriToPageAndLanguage($pageIdentifier = 0, $languageParameter = 0)
    {
        $uriBuilder = ObjectUtility::getObjectManager()->get(UriBuilder::class);
        $uriBuilder->setTargetPageUid($pageIdentifier);
        $uriBuilder->setCreateAbsoluteUri(true);
        $uriBuilder->setArguments([$this->languageParameter => $languageParameter]);
        return $uriBuilder->buildFrontendUri();
    }

    /**
     * @return array
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
     *
     * @return boolean
     */
    protected function isActivated()
    {
        $activated = $this->activated
            && ($this->isActivatedBecauseOfDifferentLanguages() || $this->isActivatedBecauseOfDifferentRootpages());

        $events = $this->getEvents();
        if (!empty($events) && $events[0] === 'none') {
            $activated = false;
        }

        return $activated;
    }

    /**
     * @return bool
     */
    protected function isActivatedBecauseOfDifferentLanguages()
    {
        $isDifferent = $this->getBestMatchingLanguageParameter() !== $this->languageUid;
        $this->isActivatedBecauseOfDifferentLanguages = $isDifferent;
        return $isDifferent;
    }

    /**
     * @return bool
     */
    protected function isActivatedBecauseOfDifferentRootpages()
    {
        $isDifferent = $this->getBestMatchingRootPage() !== $this->rootpageUid;
        $this->isActivatedBecauseOfDifferentRootpages = $isDifferent;
        return $isDifferent;
    }

    /**
     * @return RedirectService
     */
    protected function setDeactivated()
    {
        $this->activated = false;
        return $this;
    }
}
