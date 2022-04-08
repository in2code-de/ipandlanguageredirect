<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

call_user_func(
    function () {

        /**
         * Include Frontend Plugins
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ipandlanguageredirect',
            'Pi1',
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'redirect,suggest'
            ],
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'redirect'
            ]
        );
    }
);
