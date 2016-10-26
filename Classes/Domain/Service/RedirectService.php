<?php
namespace In2code\Ipandlanguageredirect\Domain\Service;

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
    protected $ipAddress = '';

    /**
     * @var string
     */
    protected $languageParameter = 'L';

    /**
     * RedirectService constructor.
     * @param string $browserLanguage
     * @param string $referrer
     * @param string $ipAddress
     */
    public function __construct($browserLanguage = '', $referrer = '', $ipAddress = '')
    {
        $this->browserLanguage = $browserLanguage;
        $this->referrer = $referrer;
        $this->ipAddress = $ipAddress;
        $this->configuration = ConfigurationUtility::getRedirectConfiguration();
    }

    /**
     * @return string
     */
    public function getRedirectUri()
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
        $configurationSet->calculateQuantifiers($this->browserLanguage, $this->referrer, $this->ipAddress);
        return $configurationSet->getBestFittingConfiguration();
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
}
