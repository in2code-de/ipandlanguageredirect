<?php

namespace In2code\Ipandlanguageredirect\Controller;

use Psr\Http\Message\ResponseInterface;
use In2code\Ipandlanguageredirect\Domain\Service\RedirectService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class RedirectController
 */
class RedirectController extends ActionController
{
    public function __construct(
        private readonly RedirectService $redirectService
    ) {}


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
     *      index.php?id=2&type=1555
     *      &tx_ipandlanguageredirect_pi1[browserLanguage]=de
     *      &tx_ipandlanguageredirect_pi1[referrer]=http://google.de/
     *      &tx_ipandlanguageredirect_pi1[ipAddress]=66.85.131.18
     *      &tx_ipandlanguageredirect_pi1[languageUid]=0
     *      &tx_ipandlanguageredirect_pi1[rootpageUid]=1
     *      &tx_ipandlanguageredirect_pi1[domain]=www.domain.org
     *
     * @param string $browserLanguage browser language
     * @param string $referrer referrer address
     * @param string $ipAddress given IP address
     * @param int $languageUid current FE language uid
     * @param int $rootpageUid current rootpage uid
     * @param string $countryCode overrides ip2country function for testing
     * @param string $domain overrides domain function for testing if given
     * @return string
     */
    public function redirectAction(
        string $browserLanguage = '',
        string $referrer = '',
        string $ipAddress = '',
        int $languageUid = 0,
        int $rootpageUid = 1,
        string $countryCode = '',
        string $domain = ''
    ): ResponseInterface {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->redirectService->set(
            $browserLanguage,
            $referrer,
            $ipAddress,
            $languageUid,
            $rootpageUid,
            $countryCode,
            $domain
        );
        return $this->jsonResponse(json_encode($this->redirectService->buildParameters()));
    }
}
