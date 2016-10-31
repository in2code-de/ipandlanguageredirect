<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IpUtility
 */
class IpUtility
{

    /**
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
     * Alternative: https://ipapi.co/208.67.222.222/json/
     *
     * @param string $ipAddress
     * @return string Countryname
     */
    public static function getCountryCodeFromIp($ipAddress = null)
    {
        if ($ipAddress === null) {
            $ipAddress = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        }
        $geoInfo = null;
        if (empty(self::$ipAddresses[$ipAddress])) {
            $json = GeneralUtility::getUrl('http://ip-api.com/json/' . $ipAddress);
            if ($json) {
                $geoInfo = json_decode($json);
                self::$ipAddresses[$ipAddress] = $geoInfo;
            }
        } else {
            $geoInfo = self::$ipAddresses[$ipAddress];
        }
        if ($geoInfo !== null && !empty($geoInfo->countryCode)) {
            return strtolower($geoInfo->countryCode);
        }
        return '';
    }
}
