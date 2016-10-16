<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Common;

use Guzzle\Http\ClientInterface;

/**
 * Class SignatureValidator
 *
 * @package Omnipay\Paysera\Common
 */
class SignatureValidator
{
    /**
     * @var string
     */
    private static $endpoint = 'http://www.paysera.com/download/public.key';

    /**
     * @param array           $data
     * @param string          $password
     * @param ClientInterface $client
     *
     * @return bool
     */
    public static function isValid(array $data, $password, ClientInterface $client)
    {
        return self::isValidSS1($data, $password) && self::isValidSS2($data, $client);
    }

    /**
     * @param array  $data
     * @param string $password
     *
     * @return bool
     */
    private static function isValidSS1(array $data, $password)
    {
        return SignatureGenerator::generate($data['data'], $password) === $data['ss1'];
    }

    /**
     * @param array           $data
     * @param ClientInterface $client
     *
     * @return bool
     */
    private static function isValidSS2(array $data, ClientInterface $client)
    {
        $response = $client->get(self::$endpoint)->send();
        if (200 === $response->getStatusCode() && false !== $publicKey = openssl_get_publickey($response->getBody())) {
            return openssl_verify($data['data'], Encoder::decode($data['ss2']), $publicKey) === 1;
        }

        return false;
    }
}
