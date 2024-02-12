<?php

declare(strict_types=1);
namespace In2code\Ipandlanguageredirect\Domain\Service\IpToCountry;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class AbstractIpToCountry
 */
abstract class AbstractIpToCountry
{
    /**
     * ip address for testing
     *
     * @var string
     */
    protected $ipAddress = '';

    /**
     * @param string $ipAddress
     */
    public function __construct(string $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    protected function getCurrentIpAddress(): string
    {
        $ipAddress = $this->ipAddress;
        if ($ipAddress === '') {
            $ipAddress = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        }
        return $ipAddress;
    }

    /**
     * @param string $className children classname
     * @param string $path like "keys.public"
     * @return array|string
     */
    protected function getConfiguration(string $className, string $path = '')
    {
        $settings = [];
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        $setup = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'Ipandlanguageredirect'
        );
        if (!empty($setup['ipToCountry'][$className])) {
            $settings = (array)$setup['ipToCountry'][$className];
        }
        if ($path !== '') {
            try {
                $settings = ArrayUtility::getValueByPath($settings, $path, '.');
            } catch (\Exception $exception) {
                unset($exception);
            }
        }
        return $settings;
    }
}
