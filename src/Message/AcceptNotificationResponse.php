<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class AcceptNotificationResponse
 *
 * @package Omnipay\Paysera\Message
 */
class AcceptNotificationResponse extends AbstractResponse implements NotificationInterface
{
    /**
     * @param RequestInterface $request
     * @param array            $data
     *
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if ($this->getDataValueOrNull('type') !== 'macro') {
            throw new InvalidResponseException('Only macro payment callbacks are accepted');
        }

        if ($this->isSuccessful()) {
            echo 'OK';
        }
    }

    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        return '1' === $this->getCode();
    }

    /**
     * @inheritdoc
     */
    public function getTransactionReference()
    {
        return $this->getDataValueOrNull('orderid');
    }

    /**
     * @inheritdoc
     */
    public function getTransactionStatus()
    {
        switch ($this->getCode()) {
            case '0':
                return NotificationInterface::STATUS_FAILED;
            case '1':
                return NotificationInterface::STATUS_COMPLETED;
            default:
                return NotificationInterface::STATUS_PENDING;
        }
    }

    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return $this->getDataValueOrNull('status');
    }

    /**
     * @return bool
     */
    public function isTestMode()
    {
        return $this->getDataValueOrNull('test') !== '0';
    }

    public function getMessage()
    {
        return $this->getDataValueOrNull('paytext');
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getDataValueOrNull($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
}
