<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Utility\ArrayUtility;
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
        $configuredLocation = self::getExtensionConfiguration('configurationFilePath');
        if (!empty($configuredLocation)) {
            $location = $configuredLocation;
        }
        return $location;
    }

    /**
     * Get extension configuration from LocalConfiguration.php
     *
     * @param string $path key or path (splitted with .)
     * @return array|string
     */
    protected static function getExtensionConfiguration($path = '')
    {
        $configVariables = self::getTypo3ConfigurationVariables();
        $configuration = unserialize($configVariables['EXT']['extConf']['ipandlanguageredirect']);
        if (!empty($path)) {
            try {
                $configuration = ArrayUtility::getValueByPath($configuration, $path, '.');
            } catch (\Exception $exception) {
                return '';
            }
        }
        return $configuration;
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
