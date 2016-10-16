<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Tests\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paysera\Common\PurchaseDataGenerator;
use Omnipay\Paysera\Common\SignatureGenerator;
use Omnipay\Paysera\Message\PurchaseRequest;
use Omnipay\Paysera\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;

/**
 * Class PurchaseRequestTest
 *
 * @package Omnipay\Paysera\Tests\Message
 */
class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    protected $request;

    /**
     * @var string
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $password;

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->projectId = uniqid('', true);
        $this->password = uniqid('', true);
    }

    public function testData()
    {
        $this->request->initialize($this->getValidParameters());

        $this->assertEquals($this->request->getData(), $this->getData());
    }

    public function testSendSuccess()
    {
        $this->request->initialize($this->getValidParameters());

        /** @var PurchaseResponse $response */
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame($response->getRedirectUrl(), 'https://www.paysera.com/pay/');
        $this->assertSame($response->getRedirectData(), $this->request->getData());
        $this->assertSame($response->getRedirectMethod(), 'POST');
    }

    public function testSendFailure_InvalidParameter_MissingRequired()
    {
        $this->setExpectedException(InvalidRequestException::class);
        $this->request->initialize($this->getParametersWithMissing());
        $this->request->send();
    }

    public function testSendFailure_InvalidParameter_TooLong()
    {
        $this->setExpectedException(InvalidRequestException::class);
        $this->request->initialize($this->getParametersWithOneTooLong());
        $this->request->send();
    }

    public function testSendFailure_InvalidParameter_Regex()
    {
        $this->setExpectedException(InvalidRequestException::class);
        $this->request->initialize($this->getParametersWithOneInvalidRegex());
        $this->request->send();
    }

    /**
     * @return array
     */
    private function getValidParameters()
    {
        return [
            'projectId' => $this->projectId,
            'password' => $this->password,
            'transactionId' => 'order_id',
            'returnUrl' => 'http://return-url.com',
            'cancelUrl' => 'http://cancel-url.com',
            'notifyUrl' => 'http://notify-url.com',
            'language' => 'LIT',
            'currency' => 'EUR',
            'amount' => 10.0,
            'customer' => [
                'firstName' => 'first_name',
                'lastName' => 'last_name',
                'email' => 'email@mail.com',
                'city' => 'city',
                'street' => 'street',
                'postCode' => 12345,
                'countryCode' => 'lt',
                'country' => 'lt',
                'state' => 'state',
            ],
        ];
    }

    /**
     * @return array
     */
    private function getParametersWithMissing()
    {
        return [
            'projectId' => $this->projectId,
            'password' => $this->password,
        ];
    }

    /**
     * @return array
     */
    private function getParametersWithOneTooLong()
    {
        $parameters = $this->getValidParameters();
        $parameters['language'] = 123;

        return $parameters;
    }

    /**
     * @return array
     */
    private function getParametersWithOneInvalidRegex()
    {
        $parameters = $this->getValidParameters();
        $parameters['transactionId'] = str_repeat('really_too_long', 100);

        return $parameters;
    }

    /**
     * @return array
     */
    private function getData()
    {
        $data = PurchaseDataGenerator::generate($this->request);

        return [
            'data' => $data,
            'sign' => SignatureGenerator::generate($data, $this->password),
        ];
    }
}
