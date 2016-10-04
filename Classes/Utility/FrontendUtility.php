<?php
namespace In2code\Ipandlanguageredirect\Utility;

/**
 * Class FrontendUtility
 */
class FrontendUtility
{
    const PLUGIN_NAME = 'tx_ipandlanguageredirect_pi1';

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
}
