<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
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
     * @param string $path key or path (splitted with .)
     * @return array|string
     */
    public static function getExtensionConfiguration($path = '')
    {
        $configVariables = self::getTypo3ConfigurationVariables();
        $configuration = '';

        if (!empty($path)) {
            try {
                $configuration = $configVariables[$path];
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

    /**
     * Get extension configuration from LocalConfiguration.php
     *
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected static function getTypo3ConfigurationVariables()
    {
        return GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('ipandlanguageredirect');
    }
}
