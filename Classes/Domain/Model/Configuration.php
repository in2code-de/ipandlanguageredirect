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

    /**
     * @param int $rootPage
     * @param int $languageParameter
     * @param array $setConfiguration
     */
    public function __construct(int $rootPage, int $languageParameter, array $setConfiguration)
    {
        $this->setIdentifier($setConfiguration['identifier'] ?? '');
        $this->setRootPage($rootPage);
        $this->setLanguageParameter($languageParameter);
        $this->setBrowserLanguages($setConfiguration['browserLanguage'] ?? []);
        $this->setCountries($setConfiguration['countryBasedOnIp'] ?? []);
        $this->setDomains($setConfiguration['domain'] ?? []);
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return Configuration
     */
    public function setIdentifier(string $identifier): Configuration
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return int
     */
    public function getRootPage(): int
    {
        return $this->rootPage;
    }

    /**
     * @param int $rootPage
     * @return Configuration
     */
    public function setRootPage(int $rootPage): Configuration
    {
        $this->rootPage = $rootPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getLanguageParameter(): int
    {
        return $this->languageParameter;
    }

    /**
     * @param int $languageParameter
     * @return Configuration
     */
    public function setLanguageParameter(int $languageParameter): Configuration
    {
        $this->languageParameter = $languageParameter;
        return $this;
    }

    /**
     * @return array
     */
    public function getBrowserLanguages(): array
    {
        return $this->browserLanguages;
    }

    /**
     * @param array $browserLanguages
     * @return Configuration
     */
    public function setBrowserLanguages(array $browserLanguages): Configuration
    {
        $this->browserLanguages = $browserLanguages;
        return $this;
    }

    /**
     * @return array
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @param array $countries
     * @return Configuration
     */
    public function setCountries(array $countries): Configuration
    {
        $this->countries = $countries;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantifier(): float
    {
        return $this->quantifier;
    }

    /**
     * @param float $quantifier
     * @return Configuration
     */
    public function setQuantifier($quantifier)
    {
        $this->quantifier = $quantifier;
        return $this;
    }

    /**
     * @return array
     */
    public function getDomains(): array
    {
        return $this->domains;
    }

    /**
     * @param array $domains
     * @return Configuration
     */
    public function setDomains(array $domains)
    {
        $this->domains = $domains;
        return $this;
    }
}
