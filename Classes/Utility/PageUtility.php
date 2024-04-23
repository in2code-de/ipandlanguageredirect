<?php

namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

/**
 * Class PageUtility
 */
class PageUtility
{
    /**
     * Checks if the given pid is the the rootline of the current page
     *
     * @param int $pid
     * @return bool
     */
    public static function isInCurrentRootline($pid): bool
    {
        $rootline = GeneralUtility::makeInstance(
            RootlineUtility::class,
            FrontendUtility::getCurrentPageIdentifier()
        )->get();
        foreach ($rootline as $page) {
            if ($page['uid'] === $pid) {
                return true;
            }
        }
        return false;
    }
}
