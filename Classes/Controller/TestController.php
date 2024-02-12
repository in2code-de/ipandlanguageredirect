<?php

namespace In2code\Ipandlanguageredirect\Controller;

use Psr\Http\Message\ResponseInterface;
use In2code\Ipandlanguageredirect\Utility\FrontendUtility;
use In2code\Ipandlanguageredirect\Utility\ObjectUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Extbase\Http\ForwardResponse;

/**+
 * Class RedirectController
 */
class TestController extends ActionController
{

    /**
     * @var array
     */
    protected $testArguments = [
        [
            'browserLanguage' => 'de',
            'referrer' => 'http://www.google.de?foo=bar',
            'ipAddress' => '192.168.0.1',
            'languageUid' => '0',
            'rootpageUid' => '1'
        ],
        [
            'browserLanguage' => 'de',
            'referrer' => 'http://www.google.de?foo=bar',
            'ipAddress' => '',
            'languageUid' => '0',
            'rootpageUid' => '1'
        ]
    ];

    public function __construct(
        private readonly ContentObjectRenderer $contentObjectRenderer
    )
    {
    }

    /**
     * Test the redirectAction directly with some predefined parameters from a given set
     *      call index.php?id=2&type=1556&tx_ipandlanguageredirect_pi1[set]=1
     *
     * @param int $set
     * @return void
     */
    public function testAction($set = 0): ResponseInterface
    {
        $configuration = [
            'parameter' => ObjectUtility::getTyposcriptFrontendController()->id,
            'additionalParams' =>
                FrontendUtility::getParametersStringFromArray($this->testArguments[$set]) . '&type=1555'
        ];

        $uri = $this->contentObjectRenderer->typoLink_URL($configuration);

        // Create redirect response
        $response = $this->responseFactory
            ->createResponse(303)
            ->withAddedHeader('location', $uri);

        // Return Response directly
        return $response;

        // or throw PropagateResponseException
        throw new PropagateResponseException($response);
    }
}
