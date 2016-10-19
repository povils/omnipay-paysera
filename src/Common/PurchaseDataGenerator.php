<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Common;

use Omnipay\Paysera\Message\PurchaseRequest;

/**
 * Class PurchaseDataGenerator
 *
 * @package Omnipay\Paysera\Common
 */
class PurchaseDataGenerator
{
    /**
     * @param PurchaseRequest $request
     *
     * @return string
     */
    public static function generate(PurchaseRequest $request)
    {
        $parameters = array(
            'projectid' => $request->getProjectId(),
            'orderid' => $request->getTransactionId(),
            'accepturl' => $request->getReturnUrl(),
            'cancelurl' => $request->getCancelUrl(),
            'callbackurl' => $request->getNotifyUrl(),
            'version' => $request->getVersion(),
            'payment' => $request->getPaymentMethod(),
            'lang' => $request->getLanguage(),
            'amount' => $request->getAmountInteger(),
            'currency' => $request->getCurrency(),
            'test' => $request->getTestMode() ? '1' : '0',
        );

        if (null !== $card = $request->getCard()) {
            $customerData = array(
                'p_firstname' => $card->getFirstName(),
                'p_lastname' => $card->getLastName(),
                'p_email' => $card->getEmail(),
                'p_street' => $card->getAddress1(),
                'p_city' => $card->getCity(),
                'p_state' => $card->getState(),
                'p_zip' => $card->getPostcode(),
                'country' => $card->getCountry(),
            );

            $parameters = array_merge($parameters, $customerData);
        }

        $filteredParameters = self::filterParameters($parameters);

        PurchaseParameterValidator::validate($filteredParameters);

        return Encoder::encode(http_build_query($filteredParameters, '', '&'));
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    private static function filterParameters(array $parameters)
    {
        return array_filter($parameters, function ($value) {
            return $value !== '' && $value !== null;
        });
    }
}
