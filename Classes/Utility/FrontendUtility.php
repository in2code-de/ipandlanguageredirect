<?php
namespace In2code\Ipandlanguageredirect\Utility;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class FrontendUtility
 */
class FrontendUtility
{
    const PLUGIN_NAME = 'tx_ipandlanguageredirect_pi1';

    /**
     * Get current page identifier
     *
     * @return int
     */
    public static function getCurrentPageIdentifier()
    {
        return (int)self::getTyposcriptFrontendController()->id;
    }

    /**
     * @param array $parameters
     * @return string
     */
    public static function getParametersStringFromArray(array $parameters)
    {
        $string = '';
        foreach ($parameters as $key => $value) {
            $string .= '&' . self::PLUGIN_NAME . '[' . $key . ']=' . $value;
        }
        return $string;
    }

    /**
     * @return TypoScriptFrontendController
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected static function getTyposcriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
