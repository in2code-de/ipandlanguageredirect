<?php
namespace In2code\Ipandlanguageredirect\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class RedirectController
 */
class RedirectController extends ActionController
{

    /**
     * @return string
     */
    public function redirectAction()
    {
        return json_encode(['dosomething' => 1]);
    }
}
