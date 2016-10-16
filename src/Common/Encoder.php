<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Common;

/**
 * Class Encoder
 *
 * @package Omnipay\Paysera\Common
 */
class Encoder
{
    /**
     * @param string $input
     *
     * @return string
     */
    public static function encode($input)
    {
        return strtr(base64_encode($input), ['+' => '-', '/' => '_']);
    }

    /**
     * @param $input
     *
     * @return string
     */
    public static function decode($input)
    {
        return base64_decode(strtr($input, ['-' => '+', '_' => '/']));
    }
}
