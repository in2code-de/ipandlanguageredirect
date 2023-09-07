<?php

declare(strict_types=1);
namespace In2code\Ipandlanguageredirect\Domain\Service\IpToCountry;

use Doctrine\DBAL\DBALException;
use In2code\Ipandlanguageredirect\Utility\DatabaseUtility;

/**
 * Class LocalDatabase
 */
class LocalDatabase extends AbstractIpToCountry implements IpToCountryInterface
{
    const TABLE_NAME = 'tx_ipandlanguageredirect_domain_model_iptocountry';

    /**
     * "zz" stands for not indexed countries. This can be converted to a real country for
     * testing cases (like on IP 127.0.0.1)
     *
     * @var string
     */
    protected $testCountry = 'de';

    /**
     * Get the countryCode from local database
     *
     * @return string
     * @throws DBALException
     */
    public function getCountryCodeFromIp(): string
    {
        $countryCode = $this->getCountryCodeFromIpInDatabase($this->getCurrentIpAddress());
        if ($countryCode === 'zz') {
            $countryCode = $this->testCountry;
        }
        return $countryCode;
    }

    /**
     * @param string $ipAddress
     * @return string
     * @throws DBALException
     */
    protected function getCountryCodeFromIpInDatabase(string $ipAddress): string
    {
        $connection = DatabaseUtility::getConnectionForTable(self::TABLE_NAME);
        $sql = 'select countryCode from ' . self::TABLE_NAME
            . ' where inet_aton("' . $this->sanitizeIpAddress($ipAddress) . '") >= inet_aton(ipRangeStart)' .
            ' and inet_aton("' . $this->sanitizeIpAddress($ipAddress) . '") <= inet_aton(ipRangeEnd) limit 1';
        $result = (string)$connection->executeQuery($sql)->fetchOne(0);
        return strtolower($result);
    }

    protected function sanitizeIpAddress(string $ipAddress): string
    {
        return preg_replace('~[^0-9\.]~', '', $ipAddress);
    }
}
