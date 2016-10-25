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
    protected $ip = '';

    /**
     * RedirectService constructor.
     * @param string $browserLanguage
     * @param string $referrer
     * @param string $ip
     */
    public function __construct($browserLanguage = '', $referrer = '', $ip = '')
    {
        $this->browserLanguage = $browserLanguage;
        $this->referrer = $referrer;
        $this->ip = $ip;
        $this->configuration = ConfigurationUtility::getRedirectConfiguration();
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->getUriToPage($this->getBestMatchingRootPage());
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
     * @return Configuration|null
     */
    protected function getBestConfiguration()
    {
        $configurationSet = ObjectUtility::getObjectManager()->get(ConfigurationSet::class, $this->configuration);
        $configurationSet->calculateQuantifiers($this->browserLanguage, $this->referrer, $this->ip);
        return $configurationSet->getBestFittingConfiguration();
    }

    /**
     * @param int $pageIdentifier
     * @return string
     */
    protected function getUriToPage($pageIdentifier = 0)
    {
        $uriBuilder = ObjectUtility::getObjectManager()->get(UriBuilder::class);
        $uriBuilder->setTargetPageUid($pageIdentifier);
        $uriBuilder->setCreateAbsoluteUri(true);
        return $uriBuilder->buildFrontendUri();
    }
}
