<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PurchaseResponse
 *
 * @package Omnipay\Paysera\Message
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getEndpoint();
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return Request::METHOD_POST;
    }

    /**
     * @return array
     */
    public function getRedirectData()
    {
        return $this->getData();
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return 'https://www.paysera.com/pay/';
    }
}
