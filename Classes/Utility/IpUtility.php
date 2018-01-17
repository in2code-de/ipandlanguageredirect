<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IpUtility
 */
class IpUtility
{

    /**
     * Array for caching IPs and countries
     *
     * @var array
     */
    protected static $ipAddresses = [];

    /**
     * Get Country Name out of an IP address and cache the object for further calls
     *
     * Some ip addresses for testing:
     *      '217.72.208.133' => 'Germany'
     *      '27.121.255.4' =>  'Japan'
     *      '5.226.31.255' => 'Spain'
     *      '66.85.131.18' => 'United States'
     *      '182.118.23.7' => 'China'
     *
     * Possible services:
     *      http://ip-api.com/json/208.67.222.222/
     *      https://ipapi.co/208.67.222.222/json/
     *
     * @param string $ipAddress pass it to the function for test cases
     * @return string Countryname
     */
    public static function getCountryCodeFromIp($ipAddress = null)
    {
        if ($ipAddress === null) {
            $ipAddress = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        }
        $geoInfo = null;
        if (empty(self::$ipAddresses[$ipAddress])) {
            $json = GeneralUtility::getUrl('https://ipapi.co/' . $ipAddress . '/json/' . self::getIpApiKey());

            if ($json) {
                $geoInfo = json_decode($json);
                self::$ipAddresses[$ipAddress] = $geoInfo;
            }
        } else {
            $geoInfo = self::$ipAddresses[$ipAddress];
        }
        if ($geoInfo !== null && !empty($geoInfo->country)) {
            return strtolower($geoInfo->country);
        }
        return '';
    }

    /**
     * @return string
     */
    protected static function getIpApiKey()
    {
        $key = '';
        $keyFromConfiguration = ConfigurationUtility::getExtensionConfiguration('ipApiKey');
        if (!empty($keyFromConfiguration)) {
            $key = '?key=' . ltrim($keyFromConfiguration, '?key=');
        }
        return $key;
    }
}
