<?php

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ipandlanguageredirect',
            'Suggest',
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'suggest'
            ],
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'suggest'
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ipandlanguageredirect',
            'Redirect',
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'redirect'
            ],
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'redirect'
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ipandlanguageredirect',
            'Test',
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'test'
            ],
            [
                \In2code\Ipandlanguageredirect\Controller\RedirectController::class => 'test'
            ]
        );
    }
);
