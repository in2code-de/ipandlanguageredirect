<?php

namespace In2code\Ipandlanguageredirect\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class RedirectController
 */
class SuggestController extends ActionController
{
    /**
     * Render a suggest container that can be slided down in FE
     *
     * @return void
     */
    public function suggestAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }
}
