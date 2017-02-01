<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

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
    public static function isInCurrentRootline($pid)
    {
        $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
        $rootline = $pageRepository->getRootLine(ObjectUtility::getTyposcriptFrontendController()->id);
        foreach ($rootline as $page) {
            if ($page['uid'] === $pid) {
                return true;
            }
        }
        return false;
    }
}
