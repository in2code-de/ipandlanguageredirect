<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class ObjectUtility
 */
class ObjectUtility
{

    /**
     * @return ObjectManager
     */
    public static function getObjectManager()
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * @param string $className
     * @return Logger
     */
    public static function getLogger(string $className): Logger
    {
        return GeneralUtility::makeInstance(LogManager::class)->getLogger($className);
    }

    /**
     * @return ContentObjectRenderer
     */
    public static function getContentObject()
    {
        return self::getObjectManager()->get(ContentObjectRenderer::class);
    }

    /**
     * @return TypoScriptFrontendController
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function getTyposcriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
