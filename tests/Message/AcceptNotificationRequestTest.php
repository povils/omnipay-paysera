<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Tests\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Paysera\Common\Encoder;
use Omnipay\Paysera\Common\SignatureGenerator;
use Omnipay\Paysera\Message\AcceptNotificationRequest;
use Omnipay\Paysera\Message\AcceptNotificationResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class AcceptNotificationRequestTest
 *
 * @package Omnipay\Paysera\Tests\Message
 */
class AcceptNotificationRequestTest extends TestCase
{
    /**
     * @var string
     */
    protected $projectId;

    /**
     * @var HttpRequest
     */
    protected $httpRequest;

    /**
     * @var string
     */
    protected $password;

    public function setUp()
    {
        $this->projectId = uniqid('', true);
        $this->password = uniqid('', true);

        $this->httpRequest = $this->getHttpRequest();
    }

    public function testSendSuccess()
    {
        $this->httpRequest->attributes->replace($this->notifyData($this->getSuccessData()));

        /** @var AcceptNotificationResponse $response */
        $response = $this->createRequest()->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($this->getSuccessData()['orderid'], $response->getTransactionReference());
        $this->assertSame(NotificationInterface::STATUS_COMPLETED, $response->getTransactionStatus());
        $this->assertSame('1', $response->getCode());
        $this->assertTrue($response->isTestMode());
        $this->assertSame($this->getSuccessData()['paytext'], $response->getMessage());
    }

    public function testSendFailed()
    {
        $failedData = $this->getSuccessData();
        $failedData['status'] = '0';

        $this->httpRequest->attributes->replace($this->notifyData($failedData));

        /** @var AcceptNotificationResponse $response */
        $response = $this->createRequest()->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(NotificationInterface::STATUS_FAILED, $response->getTransactionStatus());
        $this->assertSame('0', $response->getCode());
    }

    public function testSendPending()
    {
        $pendingData = $this->getSuccessData();
        $pendingData['status'] = '2';

        $this->httpRequest->attributes->replace($this->notifyData($pendingData));

        /** @var AcceptNotificationResponse $response */
        $response = $this->createRequest()->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(NotificationInterface::STATUS_PENDING, $response->getTransactionStatus());
        $this->assertSame('2', $response->getCode());
    }

    public function testSendFailure_SignatureIsInvalid_InvalidSS1()
    {
        $notifyData = $this->notifyData($this->getSuccessData());
        $notifyData['ss1'] = 'invalid_signature';

        $this->httpRequest->attributes->replace($notifyData);

        $this->setExpectedException(InvalidRequestException::class, 'Invalid signature');
        $this->createRequest()->send();
    }

    public function testSendFailure_SignatureIsInvalid_InvalidSS2()
    {
        $notifyData = $this->notifyData($this->getSuccessData());
        $notifyData['ss2'] = 'invalid_signature';

        $this->httpRequest->attributes->replace($notifyData);

        $this->setExpectedException(InvalidRequestException::class, 'Invalid signature');
        $this->createRequest()->send();
    }

    public function testSendFailure_SignatureIsInvalid_BadResponseForSS2()
    {
        $notifyData = $this->notifyData($this->getSuccessData());
        $notifyData['ss2'] = 'invalid_signature';

        $this->httpRequest->attributes->replace($notifyData);

        $this->setExpectedException(InvalidRequestException::class, 'Invalid signature');
        $this->createRequest()->send();
    }

    public function testSendFailure_InvalidNotifyType()
    {
        $pendingData = $this->getSuccessData();
        $pendingData['type'] = 'not_macro';

        $this->httpRequest->attributes->replace($this->notifyData($pendingData));

        $this->setExpectedException(InvalidResponseException::class);
        $this->createRequest()->send();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function notifyData(array $data)
    {
        $encodedData = $this->getEncodedData($data);

        return [
            'data' => $encodedData,
            'ss1' => SignatureGenerator::generate($encodedData, $this->password),
            'ss2' => $this->getSS2Signature($encodedData),
        ];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function getEncodedData(array $data)
    {
        return Encoder::encode(http_build_query($data, '', '&'));
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private function getSS2Signature($data)
    {
        $resource = openssl_pkey_new();
        openssl_pkey_export($resource, $privateKey);

        $privateKey = openssl_pkey_get_private($privateKey);
        openssl_sign($data, $ss2, $privateKey);

        $publicKey = openssl_pkey_get_details($resource);

        $response = $this->getMockHttpResponse('PubKeyResponse.txt');
        $response->setBody($publicKey['key']);
        $this->setMockHttpResponse([$response]);

        return Encoder::encode($ss2);
    }

    /**
     * @return array
     */
    public function getSuccessData()
    {
        return [
            'projectId' => $this->projectId,
            'orderid' => 'order_id',
            'version' => '1.6',
            'lang' => 'LIT',
            'type' => 'macro',
            'amount' => '1000',
            'currency' => 'EUR',
            'country' => 'LT',
            'paytext' => 'some information',
            'status' => '1',
            'test' => '1',
        ];
    }

    /**
     * @return AcceptNotificationRequest
     */
    private function createRequest()
    {
        $request = new AcceptNotificationRequest($this->getHttpClient(), $this->httpRequest);
        $request
            ->setProjectId($this->projectId)
            ->setPassword($this->password);

        return $request;
    }
}
