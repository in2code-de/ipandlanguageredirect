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
            'redirect',
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'redirect'
            ],
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'redirect'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ipandlanguageredirect',
            'suggest',
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'suggest'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ipandlanguageredirect',
            'test',
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'test'
            ]
        );
    }
);
