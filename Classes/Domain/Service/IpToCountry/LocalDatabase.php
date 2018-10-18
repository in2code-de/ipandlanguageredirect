<?php
declare(strict_types=1);
namespace In2code\Ipandlanguageredirect\Domain\Service\IpToCountry;

use In2code\Ipandlanguageredirect\Utility\DatabaseUtility;

/**
 * Class LocalDatabase
 */
class LocalDatabase extends AbstractIpToCountry implements IpToCountryInterface
{
    const TABLE_NAME = 'tx_ipandlanguageredirect_domain_model_iptocountry';

    /**
     * Get the countryCode from local database
     *
     * @return string
     */
    public function getCountryCodeFromIp(): string
    {
        $ipAddress = $this->getCurrentIpAddress();
        $connection = DatabaseUtility::getConnectionForTable(self::TABLE_NAME);
        $sql = 'select countryCode from ' . self::TABLE_NAME
            . ' where inet_aton("' . $ipAddress . '") >= inet_aton(ipRangeStart)' .
            ' and inet_aton("' . $ipAddress . '") <= inet_aton(ipRangeEnd) limit 1';
        $result = (string)$connection->query($sql)->fetchColumn(0);
        return strtolower($result);
    }
}
