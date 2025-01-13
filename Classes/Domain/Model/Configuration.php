<?php

namespace In2code\Ipandlanguageredirect\Domain\Model;

/**
 * Class Configuration
 */
class Configuration
{
    /**
     * @var string
     */
    protected $identifier = '';

    /**
     * @var int
     */
    protected $rootPage = 0;

    /**
     * @var int
     */
    protected $languageParameter = 0;

    /**
     * @var array
     */
    protected $browserLanguages = [];

    /**
     * @var array
     */
    protected $countries = [];

    /**
     * @var array
     */
    protected $domains = [];

    /**
     * @var float
     */
    protected $quantifier = 1.0;

    public function __construct(int $rootPage, int $languageParameter, array $setConfiguration)
    {
        $this->setIdentifier($setConfiguration['identifier'] ?? '');
        $this->setRootPage($rootPage);
        $this->setLanguageParameter($languageParameter);
        $this->setBrowserLanguages($setConfiguration['browserLanguage'] ?? []);
        $this->setCountries($setConfiguration['countryBasedOnIp'] ?? []);
        $this->setDomains($setConfiguration['domain'] ?? []);
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): Configuration
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getRootPage(): int
    {
        return $this->rootPage;
    }

    public function setRootPage(int $rootPage): Configuration
    {
        $this->rootPage = $rootPage;
        return $this;
    }

    public function getLanguageParameter(): int
    {
        return $this->languageParameter;
    }

    public function setLanguageParameter(int $languageParameter): Configuration
    {
        $this->languageParameter = $languageParameter;
        return $this;
    }

    public function getBrowserLanguages(): array
    {
        return $this->browserLanguages;
    }

    public function setBrowserLanguages(array $browserLanguages): Configuration
    {
        $this->browserLanguages = $browserLanguages;
        return $this;
    }

    public function getCountries(): array
    {
        return $this->countries;
    }

    public function setCountries(array $countries): Configuration
    {
        $this->countries = $countries;
        return $this;
    }

    public function getQuantifier(): float
    {
        return $this->quantifier;
    }

    /**
     * @param float $quantifier
     */
    public function setQuantifier($quantifier): static
    {
        $this->quantifier = $quantifier;
        return $this;
    }

    public function getDomains(): array
    {
        return $this->domains;
    }

    public function setDomains(array $domains): static
    {
        $this->domains = $domains;
        return $this;
    }
}
