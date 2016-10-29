<?php
namespace In2code\Ipandlanguageredirect\Controller;

use In2code\Ipandlanguageredirect\Domain\Service\RedirectService;
use In2code\Ipandlanguageredirect\Utility\FrontendUtility;
use In2code\Ipandlanguageredirect\Utility\ObjectUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException;

/**
 * Class RedirectController
 */
class RedirectController extends ActionController
{

    /**
     * @var array
     */
    protected $testArguments = [
        [
            'browserLanguage' => 'de',
            'ip' => '192.168.0.1',
            'referrer' => 'http://www.google.de?foo=bar'
        ],
        [
            'browserLanguage' => 'de',
            'ip' => '',
            'referrer' => 'http://www.google.de?foo=bar'
        ]
    ];

    /**
     * Enrich call with ip-address if not given
     * 
     * @throws InvalidArgumentNameException
     */
    public function initializeRedirectAction()
    {
        $arguments = $this->request->getArguments();
        if (empty($arguments['ipAddress'])) {
            $this->request->setArgument('ipAddress', GeneralUtility::getIndpEnv('REMOTE_ADDR'));
        }
    }

    /**
     * Can be tested with a direct call:
     *      index.php?id=2&type=1555&tx_ipandlanguageredirect_pi1[browserLanguage]=de
     *
     * @param string $browserLanguage
     * @param string $referrer
     * @param string $currentUri
     * @param string $ipAddress
     * @return string
     */
    public function redirectAction($browserLanguage = '', $referrer = '', $currentUri = '', $ipAddress = '')
    {
        $redirectService = $this->objectManager->get(
            RedirectService::class,
            $browserLanguage,
            $referrer,
            $currentUri,
            $ipAddress
        );
        return json_encode($redirectService->buildParameters());
    }

    /**
     * Test the redirectAction directly with some predefined parameters from a given set
     *      call index.php?id=2&type=1556&tx_ipandlanguageredirect_pi1[set]=1
     *
     * @param int $set
     * @return void
     */
    public function testAction($set = 0)
    {
        $configuration = [
            'parameter' => ObjectUtility::getTyposcriptFrontendController()->id,
            'additionalParams' =>
                FrontendUtility::getParametersStringFromArray($this->testArguments[$set]) . '&type=1555'
        ];
        $uri = ObjectUtility::getContentObject()->typoLink_URL($configuration);
        HttpUtility::redirect($uri, HttpUtility::HTTP_STATUS_307);
    }
}
