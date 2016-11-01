<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ConfigurationUtility
 * @SuppressWarnings(PHPMD)
 */
class ConfigurationUtility
{
    const CONFIGURATION_PATH = 'EXT:ipandlanguageredirect/Configuration/Redirect/Redirect.php';

    /**
     * @return array
     */
    public static function getRedirectConfiguration()
    {
        return include_once GeneralUtility::getFileAbsFileName(self::getConfigurationLocation());
    }

    /**
     * @return string
     */
    protected static function getConfigurationLocation()
    {
        $location = self::CONFIGURATION_PATH;
        $extensionConfig = self::getExtensionConfiguration();
        if (!empty($extensionConfig['configurationFilePath'])) {
            $location = $extensionConfig['configurationFilePath'];
        }
        return $location;
    }

    /**
     * Get extension configuration from LocalConfiguration.php
     *
     * @return array
     */
    protected static function getExtensionConfiguration()
    {
        $configVariables = self::getTypo3ConfigurationVariables();
        return unserialize($configVariables['EXT']['extConf']['ipandlanguageredirect']);
    }

    /**
     * Get extension configuration from LocalConfiguration.php
     *
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected static function getTypo3ConfigurationVariables()
    {
        return $GLOBALS['TYPO3_CONF_VARS'];
    }
}
