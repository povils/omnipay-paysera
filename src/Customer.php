<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera;

use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class Customer
 *
 * @package Omnipay\Paysera
 */
class Customer
{
    /**
     * @var ParameterBag
     */
    protected $parameters;

    /**
     * @param array|null $parameters
     */
    public function __construct(array $parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * @param array|null $parameters
     *
     * @return $this
     */
    public function initialize(array $parameters = null)
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setFirstName($value)
    {
        return $this->setParameter('firstName', $value);
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getParameter('lastName');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setLastName($value)
    {
        return $this->setParameter('lastName', $value);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->getParameter('city');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setCity($value)
    {
        return $this->setParameter('city', $value);
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->getParameter('street');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setStreet($value)
    {
        return $this->setParameter('street', $value);
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->getParameter('postCode');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setPostcode($value)
    {
        return $this->setParameter('postCode', $value);
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->getParameter('state');
    }

    /**
     * @param string $value
     *
     * @return Customer
     */
    public function setState($value)
    {
        return $this->setParameter('state', $value);
    }
}
