<?php

/**
 * This is the part of Povils open-source library.
 *
 * @author Povilas Susinskas
 */

namespace Omnipay\Paysera\Tests;

use Omnipay\Paysera\Gateway;
use Omnipay\Paysera\Message\AcceptNotificationRequest;
use Omnipay\Paysera\Message\PurchaseRequest;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class GatewayTest
 *
 * @package Omnipay\Paysera\Tests
 */
class GatewayTest extends GatewayTestCase
{
    /**
     * @var string
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->projectId = uniqid('', true);
        $this->password = uniqid('', true);
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway
            ->setProjectId($this->projectId)
            ->setPassword($this->password);
    }

    public function testDefaultParameters()
    {
        $this->assertSame($this->projectId, $this->gateway->getProjectId());
        $this->assertSame($this->password, $this->gateway->getPassword());
        $this->assertTrue($this->gateway->getTestMode());
    }

    public function testPurchase()
    {
        $this->assertTrue($this->gateway->supportsPurchase());

        $request = $this->gateway->purchase(['amount' => 10.5]);
        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame(1050, $request->getAmountInteger());
    }

    public function testAcceptNotification()
    {
        $this->assertTrue($this->gateway->supportsAcceptNotification());

        $request = $this->gateway->acceptNotification();
        $this->assertInstanceOf(AcceptNotificationRequest::class, $request);
    }
}
