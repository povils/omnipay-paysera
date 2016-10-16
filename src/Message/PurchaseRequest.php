<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Message;

use Omnipay\Paysera\Common\PurchaseDataGenerator;
use Omnipay\Paysera\Common\SignatureGenerator;
use Omnipay\Paysera\Customer;

/**
 * Class PurchaseRequest
 *
 * @package Omnipay\Paysera\Message
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('projectId', 'password');

        $data = PurchaseDataGenerator::generate($this);

        return [
            'data' => $data,
            'sign' => SignatureGenerator::generate($data, $this->getPassword()),
        ];
    }

    /**
     * @param array $data
     *
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->getParameter('version');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->getParameter('customer');
    }

    /**
     * @param Customer $value
     *
     * @return $this
     */
    public function setCustomer($value)
    {
        if ($value && false === $value instanceof Customer) {
            $value = new Customer($value);
        }

        return $this->setParameter('customer', $value);
    }
}
