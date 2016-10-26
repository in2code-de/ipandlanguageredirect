<?php
namespace In2code\Ipandlanguageredirect\Domain\Model;

/**
 * Class Configuration
 */
class Configuration
{

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
     * @var float
     */
    protected $quantifier = 1.0;

    /**
     * Configuration constructor.
     *
     * @param int $rootPage
     * @param int $languageParameter
     * @param array $setConfiguration
     */
    public function __construct($rootPage, $languageParameter, array $setConfiguration)
    {
        $this->setRootPage($rootPage);
        $this->setLanguageParameter($languageParameter);
        $this->setBrowserLanguages($setConfiguration['browserLanguage']);
        $this->setCountries($setConfiguration['countryBasedOnIp']);
    }

    /**
     * @return int
     */
    public function getRootPage()
    {
        return $this->rootPage;
    }

    /**
     * @param int $rootPage
     * @return Configuration
     */
    public function setRootPage($rootPage)
    {
        $this->rootPage = $rootPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getLanguageParameter()
    {
        return $this->languageParameter;
    }

    /**
     * @param int $languageParameter
     * @return Configuration
     */
    public function setLanguageParameter($languageParameter)
    {
        $this->languageParameter = $languageParameter;
        return $this;
    }

    /**
     * @return array
     */
    public function getBrowserLanguages()
    {
        return $this->browserLanguages;
    }

    /**
     * @param array $browserLanguages
     * @return Configuration
     */
    public function setBrowserLanguages($browserLanguages)
    {
        $this->browserLanguages = $browserLanguages;
        return $this;
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param array $countries
     * @return Configuration
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantifier()
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
}
