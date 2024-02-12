<?php

namespace In2code\Ipandlanguageredirect\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * @var int
     */
    protected $rootpageUid = 0;

    /**
     * @param array $configuration
     */
    public function __construct(array $configuration, int $rootpageUid)
    {
        $this->rawQuantifierConfiguration = $configuration['quantifier'];
        $this->rawNoMatchingConfiguration = $configuration['noMatchingConfiguration'];
        $this->rawRedirectConfiguration = $configuration['redirectConfiguration'];
        foreach ($this->rawRedirectConfiguration as $pageIdentifier => $treeConfiguration) {
            foreach ($treeConfiguration as $languageParameter => $setConfiguration) {
                $configuration = GeneralUtility::makeInstance(
                    Configuration::class,
                    $pageIdentifier,
                    $languageParameter,
                    $setConfiguration
                );
                $this->addConfiguration($configuration);
            }
        }
        $this->rootpageUid = $rootpageUid;
    }

    /**
     * @param string $browserLanguage
     * @param string $countryCode
     * @param string $domain
     */
    public function calculateQuantifiers(string $browserLanguage = '', string $countryCode = '', string $domain = '')
    {
        $configurations = $this->getConfigurations();
        foreach ($configurations as $configuration) {
            $browserQuantifier = $this->getQuantifier(
                'browserLanguage',
                $configuration->getBrowserLanguages(),
                $browserLanguage
            );
            $regionQuantifier = $this->getQuantifier('countryBasedOnIp', $configuration->getCountries(), $countryCode);
            $domainQuantifier = $this->getQuantifier('domain', $configuration->getDomains(), $domain);
            $configuration->setQuantifier($browserQuantifier * $regionQuantifier * $domainQuantifier);
        }
    }

    /**
     * Calculate a single quantifier by given key
     *
     * @param string $key "browserLanguage", "countryBasedOnIp"
     * @param array $configuration
     * @param string $givenValue - e.g. "*" or "de"
     * @return int
     */
    protected function getQuantifier(string $key, array $configuration, string $givenValue): int
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
    public function getConfigurations(): array
    {
        return $this->configurations;
    }

    /**
     * @return Configuration[]
     */
    public function getConfigurationsForCurrentRootpageUid(): array
    {
        $configurations = $this->configurations;
        foreach (array_keys($configurations) as $key) {
            if ($configurations[$key]->getRootPage() !== $this->rootpageUid) {
                unset($configurations[$key]);
            }
        }
        return $configurations;
    }

    /**
     * @param Configuration[] $configurations
     * @return ConfigurationSet
     */
    public function setConfigurations(array $configurations): ConfigurationSet
    {
        $this->configurations = $configurations;
        return $this;
    }

    /**
     * @param Configuration $configuration
     * @return ConfigurationSet
     */
    public function addConfiguration(Configuration $configuration): ConfigurationSet
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
        foreach ($this->getConfigurationsForCurrentRootpageUid() as $configuration) {
            /** @var Configuration $bestConfiguration */
            if ($bestConfiguration === null || $configuration->getQuantifier() > $bestConfiguration->getQuantifier()) {
                $bestConfiguration = $configuration;
            }
        }
        $this->getBestFittingConfigurationFromNoMatchingConfiguration($bestConfiguration);
        return $bestConfiguration;
    }

    /**
     * Find a configuration by it's identifier
     *
     * @param string $identifier
     * @return Configuration|null
     */
    public function getConfigurationByIdentifier($identifier)
    {
        foreach ($this->getConfigurations() as $configuration) {
            if ($configuration->getIdentifier() === $identifier) {
                return $configuration;
            }
        }
        return null;
    }

    /**
     * If there is no best matching configuration or if the best matching configuration has a too low quantifier
     *
     * @param Configuration|null $bestConfiguration
     * @return Configuration|null
     */
    protected function getBestFittingConfigurationFromNoMatchingConfiguration($bestConfiguration = null)
    {
        if ($bestConfiguration === null ||
            $bestConfiguration->getQuantifier() < $this->rawNoMatchingConfiguration['matchMinQuantifier']) {
            $noMatchingConfiguration = $this->getConfigurationByIdentifier(
                $this->rawNoMatchingConfiguration['identifierUsage']
            );
            if ($noMatchingConfiguration !== null) {
                $bestConfiguration = $noMatchingConfiguration;
            }
        }
        return $bestConfiguration;
    }
}
