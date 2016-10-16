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
        $parameters = [
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
        ];

        if(null !== $customer = $request->getCustomer()){
            $customerData = [
                'p_firstname' => $customer->getFirstName(),
                'p_lastname' => $customer->getLastName(),
                'p_email' => $customer->getEmail(),
                'p_street' => $customer->getStreet(),
                'p_city' => $customer->getCity(),
                'p_state' => $customer->getState(),
                'p_zip' => $customer->getPostcode(),
                'p_countrycode' => $customer->getCountryCode(),
                'country' => $customer->getCountry(),
            ];

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
