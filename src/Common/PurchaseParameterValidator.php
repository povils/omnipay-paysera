<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Common;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class PurchaseParameterValidator
 *
 * @package Omnipay\Paysera\Common
 */
class PurchaseParameterValidator
{
    /**
     * @param array $data
     *
     * @throws InvalidRequestException
     */
    public static function validate(array $data)
    {
        foreach (self::getRequestSpecifications() as $specification) {
            list($name, $maxLength, $required, $regexp) = $specification;
            if ($required && false === isset($data[$name])) {
                throw new InvalidRequestException(sprintf("'%s' is required but missing.", $name));
            }

            if (false === empty($data[$name])) {
                if ($maxLength && strlen($data[$name]) > $maxLength) {
                    throw new InvalidRequestException(sprintf(
                        "'%s' value is too long (%d), %d characters allowed.",
                        $name,
                        strlen($data[$name]),
                        $maxLength
                    ));
                }

                if ($regexp !== '' && !preg_match($regexp, $data[$name])) {
                    throw new InvalidRequestException(sprintf("'%s' value '%s' is invalid.", $name, $data[$name]));
                }
            }
        }
    }

    /**
     * Array structure:
     *   name      – request parameter name
     *   maxLength – max allowed length for parameter
     *   required  – is this parameter required
     *   regexp    – regexp to test parameter value
     *
     * @return array
     */
    protected static function getRequestSpecifications()
    {
        return [
            ['orderid', 40, true, ''],
            ['accepturl', 255, true, ''],
            ['cancelurl', 255, true, ''],
            ['callbackurl', 255, true, ''],
            ['lang', 3, false, '/^[a-z]{3}$/i'],
            ['amount', 11, false, '/^\d+$/'],
            ['currency', 3, false, '/^[a-z]{3}$/i'],
            ['payment', 20, false, ''],
            ['country', 2, false, '/^[a-z_]{2}$/i'],
            ['p_firstname', 255, false, ''],
            ['p_lastname', 255, false, ''],
            ['p_email', 255, false, ''],
            ['p_street', 255, false, ''],
            ['p_city', 255, false, ''],
            ['p_state', 20, false, ''],
            ['p_zip', 20, false, ''],
            ['p_countrycode', 2, false, '/^[a-z]{2}$/i'],
            ['test', 1, false, '/^[01]$/'],
        ];
    }
}
