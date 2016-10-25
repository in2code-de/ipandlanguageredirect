<?php
namespace In2code\Ipandlanguageredirect\Domain\Model;

use In2code\Ipandlanguageredirect\Utility\IpUtility;
use In2code\Ipandlanguageredirect\Utility\ObjectUtility;

/**
 * Class ConfigurationSet
 */
class ConfigurationSet
{

    /**
     * @var Configuration[]
     */
    protected $configurations = [];

    /**
     * @var array
     */
    protected $rawQuantifierConfiguration = [];

    /**
     * @var array
     */
    protected $rawNoMatchingConfiguration = [];

    /**
     * @var array
     */
    protected $rawRedirectConfiguration = [];

    /**
     * ConfigurationSet constructor.
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->rawQuantifierConfiguration = $configuration['quantifier'];
        $this->rawNoMatchingConfiguration = $configuration['noMatchingConfiguration'];
        $this->rawRedirectConfiguration = $configuration['redirectConfiguration'];
        foreach ($this->rawRedirectConfiguration as $pageIdentifier => $treeConfiguration) {
            foreach ($treeConfiguration as $setConfiguration) {
                $configuration = ObjectUtility::getObjectManager()->get(
                    Configuration::class,
                    $pageIdentifier,
                    $setConfiguration
                );
                $this->addConfiguration($configuration);
            }
        }
    }

    /**
     * Calculate quantifiers for Configuration
     *
     * @param string $browserLanguage
     * @param string $referrer
     * @param string $ip
     * @return void
     */
    public function calculateQuantifiers($browserLanguage = '', $referrer = '', $ip = '')
    {
        $configurations = $this->getConfigurations();
        foreach ($configurations as $configuration) {
            $browserQuantifier = $this->getQuantifier(
                'browserLanguage',
                $configuration->getBrowserLanguages(),
                $browserLanguage
            );
            $regionQuantifier = $this->getQuantifier(
                'countryBasedOnIp',
                $configuration->getCountries(),
                IpUtility::getCountryCodeFromIp($ip)
            );
            $configuration->setQuantifier($browserQuantifier * $regionQuantifier);
        }
    }

    /**
     * Calculate a single quantifier by given key
     *
     * @param string $key
     * @param array $configuration
     * @param string $givenValue
     * @return int
     */
    protected function getQuantifier($key, array $configuration, $givenValue)
    {
        $quantifier = 1;
        foreach ($configuration as $singleConfiguration) {
            $multiplier = 1;
            if ($singleConfiguration === $givenValue) {
                // direct match
                $multiplier = (int)$this->rawQuantifierConfiguration[$key]['totalMatch'];
            } elseif ($singleConfiguration === '*') {
                // wildcardmatch
                $multiplier = (int)$this->rawQuantifierConfiguration[$key]['wildCardMatch'];
            }
            if ($multiplier > 0) {
                $quantifier *= $multiplier;
            }
        }
        return $quantifier;
    }

    /**
     * @return Configuration[]
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }

    /**
     * @param Configuration[] $configurations
     * @return ConfigurationSet
     */
    public function setConfigurations($configurations)
    {
        $this->configurations = $configurations;
        return $this;
    }

    /**
     * @param Configuration $configuration
     * @return ConfigurationSet
     */
    public function addConfiguration(Configuration $configuration)
    {
        $this->configurations[] = $configuration;
        return $this;
    }

    /**
     * Return configuration with the highest quantifier
     * 
     * @return Configuration|null
     */
    public function getBestFittingConfiguration()
    {
        $bestConfiguration = null;
        foreach ($this->getConfigurations() as $configuration) {
            /** @var Configuration $bestConfiguration */
            if ($bestConfiguration === null || $configuration->getQuantifier() > $bestConfiguration->getQuantifier()) {
                $bestConfiguration = $configuration;
            }
        }
        return $bestConfiguration;
    }
}
