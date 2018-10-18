<?php
declare(strict_types=1);
namespace In2code\Ipandlanguageredirect\Domain\Service\IpToCountry;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IpApiCom
 */
class IpApiCom extends AbstractIpToCountry implements IpToCountryInterface
{

    /**
     * Array for caching IPs and countries
     *
     * @var array
     */
    protected $ipAddresses = [];

    /**
     * Get CountryCode out of an IP address (use service ip-api.com - over unsecure port 80 - 443 not supported)
     *      Some ip addresses for testing:
     *          '217.72.208.133' => 'Germany'
     *          '27.121.255.4' =>  'Japan'
     *          '5.226.31.255' => 'Spain'
     *          '66.85.131.18' => 'United States'
     *          '182.118.23.7' => 'China'
     *      Possible call: http://ip-api.com/json/27.121.255.4 (without ending slash)
     *
     * @return string CountryCode (ISO 3166) like "de"
     */
    public function getCountryCodeFromIp(): string
    {
        $ipAddress = $this->getCurrentIpAddress();
        $uri = 'http://ip-api.com/json/' . $ipAddress;
        $json = GeneralUtility::getUrl($uri);
        if ($json !== false) {
            $geoInfo = json_decode($json);
            try {
                return strtolower((string)$geoInfo->countryCode);
            } catch (\Exception $exception) {
                throw new \LogicException('Country could not be determined from ip-api.com', 1539866103);
            }
        } else {
            throw new \LogicException('Error when try to connect to ip-api.com', 1539866106);
        }
    }
}
