<?php

declare(strict_types=1);
namespace In2code\Ipandlanguageredirect\Domain\Service\IpToCountry;

/**
 * Interface IpToCountryInterface
 */
interface IpToCountryInterface
{
    public function getCountryCodeFromIp(): string;
}
