<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera;

use Omnipay\Common\AbstractGateway;
use Omnipay\Paysera\Message\AcceptNotificationRequest;
use Omnipay\Paysera\Message\PurchaseRequest;

/**
 * Class Gateway
 *
 * @package Omnipay\Paysera
 */
class Gateway extends AbstractGateway
{
    const VERSION = '1.6';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Paysera';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'testMode' => true,
            'version' => self::VERSION,
        ];
    }

    /**
     * @return string
     */
    public function getProjectId()
    {
        return $this->getParameter('projectId');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setProjectId($value)
    {
        return $this->setParameter('projectId', $value);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
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
     * @param array $parameters
     *
     * @return PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @return PurchaseRequest
     */
    public function acceptNotification()
    {
        return $this->createRequest(AcceptNotificationRequest::class, []);
    }
}
