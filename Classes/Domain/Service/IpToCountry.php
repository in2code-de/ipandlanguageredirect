<?php
namespace In2code\Ipandlanguageredirect\Domain\Service;

use In2code\Ipandlanguageredirect\Domain\Service\IpToCountry\IpToCountryInterface;
use In2code\Ipandlanguageredirect\Utility\ConfigurationUtility;
use In2code\Ipandlanguageredirect\Utility\ObjectUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IpToCountry
 */
class IpToCountry
{
    /**
     * @var string
     */
    protected $interface = IpToCountryInterface::class;

    /**
     * @param string $ipAddress if given use this (normally for testing), if empty automaticly get current IP
     * @return string
     */
    public function getCountryFromIp(string $ipAddress = ''): string
    {
        $countryCode = '';
        foreach ($this->getClasses() as $class) {
            /** @var IpToCountryInterface $countryFromIp */
            $countryFromIp = ObjectUtility::getObjectManager()->get($class, $ipAddress);
            try {
                $countryCode = $countryFromIp->getCountryCodeFromIp();
            } catch (\Exception $exception) {
                $this->logFailingOfCountryCode($class, $exception);
            }
            if (strlen($countryCode) === 2) {
                break;
            }
        }
        if (strlen($countryCode) !== 2) {
            $this->logFailingOfCountryCode($class, new \LogicException('Country could not be determined', 1539888615));
        }
        return $countryCode;
    }

    /**
     * Return countryFromIp classes from Extension Manager settings
     *
     * @return array
     */
    protected function getClasses(): array
    {
        $classList = ConfigurationUtility::getExtensionConfiguration('ipToCountryService');
        if (!empty($classList)) {
            $classes = GeneralUtility::trimExplode(',', $classList, true);
            foreach ($classes as $class) {
                if (class_exists($class) === false) {
                    throw new \UnexpectedValueException('Class ' . $class . ' does not exists', 1539859535);
                }
                if (is_subclass_of($class, $this->interface) === false) {
                    throw new \UnexpectedValueException(
                        'Class ' . $class . ' does not implement needed interface ' . $this->interface,
                        1539859593
                    );
                }
            }
            return $classes;
        } else {
            throw new \UnexpectedValueException(
                'No IpToCountryService classes given. Pls check your settings in the extension manager',
                1539859385
            );
        }
    }

    /**
     * @param string $class
     * @param \Exception $exception
     * @return void
     */
    protected function logFailingOfCountryCode(string $class, \Exception $exception)
    {
        $logger = ObjectUtility::getLogger($class);
        $logger->debug('Executing of class failed', [$exception->getMessage()]);
    }
}
