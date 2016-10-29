<?php
namespace In2code\Ipandlanguageredirect\Domain\Service;

use In2code\Ipandlanguageredirect\Domain\Model\ActionSet;
use In2code\Ipandlanguageredirect\Domain\Model\Configuration;
use In2code\Ipandlanguageredirect\Domain\Model\ConfigurationSet;
use In2code\Ipandlanguageredirect\Utility\ConfigurationUtility;
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
    protected $currentUri = '';

    /**
     * @var string
     */
    protected $ipAddress = '';

    /**
     * @var string
     */
    protected $languageParameter = 'L';

    /**
     * @var bool
     */
    protected $error = false;

    /**
     * @var array
     */
    protected $defaultParameters = [
        'error' => true,
        'events' => ['none']
    ];

    /**
     * RedirectService constructor.
     * @param string $browserLanguage
     * @param string $referrer
     * @param string $currentUri
     * @param string $ipAddress
     */
    public function __construct($browserLanguage = '', $referrer = '', $currentUri = '', $ipAddress = '')
    {
        $this->browserLanguage = $browserLanguage;
        $this->referrer = $referrer;
        $this->currentUri = $currentUri;
        $this->ipAddress = $ipAddress;
        $this->configuration = ConfigurationUtility::getRedirectConfiguration();
    }

    /**
     * @return array
     */
    public function buildParameters()
    {
        $uri = $this->getRedirectUri();
        $parameters = $this->defaultParameters;
        if (!empty($uri)) {
            $parameters = [
                'uri' => $uri,
                'error' => $this->isError(),
                'events' => $this->getEvents()
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
        $configurationSet = ObjectUtility::getObjectManager()->get(ConfigurationSet::class, $this->configuration);
        $configurationSet->calculateQuantifiers($this->browserLanguage, $this->ipAddress);
        $bestConfiguration = $configurationSet->getBestFittingConfiguration();
        if ($bestConfiguration === null) {
            $this->setError();
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
        if ($languageParameter > 0) {
            $uriBuilder->setArguments([$this->languageParameter => $languageParameter]);
        }
        return $uriBuilder->buildFrontendUri();
    }

    /**
     * @return array
     */
    protected function getEvents()
    {
        $actionSet = ObjectUtility::getObjectManager()->get(ActionSet::class, $this->configuration);
        $actionSet->calculateQuantifiers($this->referrer);
        return $actionSet->getEvents();
    }

    /**
     * @return boolean
     */
    protected function isError()
    {
        return $this->error;
    }

    /**
     * @return RedirectService
     */
    protected function setError()
    {
        $this->error = true;
        return $this;
    }
}
