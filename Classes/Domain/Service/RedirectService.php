<?php
namespace In2code\Ipandlanguageredirect\Domain\Service;

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
    protected $configuartion = [];

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
        $this->configuartion = ConfigurationUtility::getRedirectConfiguration();
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->getUriToPage($this->getBestFittingPageIdentifier());
    }

    /**
     * @return int
     */
    protected function getBestFittingPageIdentifier()
    {
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this, 'in2code: ' . __CLASS__ . ':' . __LINE__);
        return 1;
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
