<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IpUtility
 */
class IpUtility
{

    /**
     * Get Country Name out of an IP address
     *
     * Some ip addresses for testing:
     *      '217.72.208.133' => 'Germany'
     *      '27.121.255.4' =>  'Japan'
     *      '5.226.31.255' => 'Spain'
     *      '66.85.131.18' => 'United States'
     *      '182.118.23.7' => 'China'
     *
     * @param string $ipAddress
     * @return string Countryname
     */
    public static function getCountryCodeFromIp($ipAddress = null)
    {
        if ($ipAddress === null) {
            $ipAddress = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        }
        $json = GeneralUtility::getUrl('http://ip-api.com/json/' . $ipAddress);
        if ($json) {
            $geoInfo = json_decode($json);
            if (!empty($geoInfo->countryCode)) {
                return strtolower($geoInfo->countryCode);
            }
        }
        return '';
    }
}
