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
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'redirect'
            ],
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'redirect'
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ipandlanguageredirect',
            'Suggest',
            [
                \In2code\Ipandlanguageredirect\Controller\SuggestController::class => 'suggest'
            ],
            [
                \In2code\Ipandlanguageredirect\Controller\SuggestController::class => 'suggest'
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ipandlanguageredirect',
            'Test',
            [
                \In2code\Ipandlanguageredirect\Controller\TestController::class => 'test'
            ],
            [
                \In2code\Ipandlanguageredirect\Controller\TestController::class => 'test'
            ]
        );
    }
);
