<?php
defined('TYPO3_MODE') || die();

/**
 * Add TypoScript Static Template
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'ipandlanguageredirect',
    'Configuration/TypoScript/',
    'Main TypoScript'
);