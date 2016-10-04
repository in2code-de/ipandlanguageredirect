<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}
call_user_func(function () {

    /**
     * Include Plugins
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'ipandlanguageredirect',
        'Pi1',
        'Ipandlanguageredirect'
    );

    /**
     * Add TypoScript Static Template
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'ipandlanguageredirect',
        'Configuration/TypoScript/',
        'Main TypoScript'
    );
});
