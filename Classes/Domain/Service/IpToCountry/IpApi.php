<?php
declare(strict_types=1);
namespace In2code\Ipandlanguageredirect\Domain\Service\IpToCountry;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IpApi
 */
class IpApi extends AbstractIpToCountry implements IpToCountryInterface
{

    /**
     * Array for caching IPs and countries
     *
     * @var array
     */
    protected $ipAddresses = [];

    /**
     * Get CountryCode out of an IP address (use service ipapi.co)
     *      Some ip addresses for testing:
     *          '217.72.208.133' => 'Germany'
     *          '27.121.255.4' =>  'Japan'
     *          '5.226.31.255' => 'Spain'
     *          '66.85.131.18' => 'United States'
     *          '182.118.23.7' => 'China'
     *      Possible call: https://ipapi.co/208.67.222.222/json/
     *
     * @return string CountryCode (ISO 3166) like "de"
     */
    public function getCountryCodeFromIp(): string
    {
        $ipAddress = $this->getCurrentIpAddress();
        $geoInfo = null;
        if (empty($this->ipAddresses[$ipAddress])) {
            $uri = 'https://ipapi.co/' . $ipAddress . '/json/' . $this->getConfiguration(__CLASS__, 'ipApiKey');
            $json = GeneralUtility::getUrl($uri);
            if ($json !== false) {
                $geoInfo = json_decode($json);
                $this->ipAddresses[$ipAddress] = $geoInfo;
            } else {
                throw new \LogicException('Error when try to connect to ipapi.co', 1539860814);
            }
        } else {
            $geoInfo = $this->ipAddresses[$ipAddress];
        }
        if ($geoInfo !== null && !empty($geoInfo->country)) {
            return strtolower($geoInfo->country);
        }
        throw new \LogicException('Country could not be determined from ipapi.co', 1539860877);
    }
}
