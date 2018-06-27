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
     * Get extension configuration from LocalConfiguration.php
     *
     * @param string $setting key or path (splitted with .)
     * @return array|string
     */
    public static function getExtensionConfiguration($setting = '')
    {

        $configurationManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
        $settings = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'ipandlanguageredirect',
            'Pi1');

        if (!empty($setting)) {
            try {
                $configuration = $settings[$setting];
            } catch (\Exception $exception) {
                return '';
            }
        }
        return $configuration;
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

}
