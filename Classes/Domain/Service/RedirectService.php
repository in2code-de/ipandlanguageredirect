<?php
namespace In2code\Ipandlanguageredirect\Domain\Service;

/**
 * Class RedirectService
 */
class RedirectService
{

    /**
     * Get redirect URI
     *
     * @param string $browserLanguage
     * @param string $referrer
     * @param string $ip
     * @return string
     */
    public function getRedirectUri($browserLanguage = '', $referrer = '', $ip = '')
    {
        return 'index.php';
    }
}
