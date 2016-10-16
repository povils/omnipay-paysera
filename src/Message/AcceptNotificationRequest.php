<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paysera\Common\Encoder;
use Omnipay\Paysera\Common\SignatureValidator;

/**
 * Class AcceptNotificationRequest
 *
 * @package Omnipay\Paysera\Message
 */
class AcceptNotificationRequest extends AbstractRequest
{
    public function getData()
    {
        return [
            'data' => $this->httpRequest->get('data'),
            'ss1' => $this->httpRequest->get('ss1'),
            'ss2' => $this->httpRequest->get('ss2'),
        ];
    }

    /**
     * @param array $data
     *
     * @return AcceptNotificationResponse
     * @throws InvalidRequestException
     */
    public function sendData($data)
    {
        if (false === SignatureValidator::isValid($data, $this->getPassword(), $this->httpClient)) {
            throw new InvalidRequestException('Invalid signature');
        }

        return $this->response = new AcceptNotificationResponse($this, $this->parseData($data['data']));
    }

    /**
     * @param string $data
     *
     * @return array
     */
    protected function parseData($data)
    {
        $parameters = [];
        parse_str(Encoder::decode($data), $parameters);

        return $parameters;
    }
}
