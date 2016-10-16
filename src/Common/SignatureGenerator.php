<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Common;

/**
 * Class SignatureGenerator
 *
 * @package Omnipay\Paysera\Common
 */
class SignatureGenerator
{
    /**
     * @param string $data
     * @param string $password
     *
     * @return string
     */
    public static function generate($data, $password)
    {
        return md5($data . $password);
    }
}
