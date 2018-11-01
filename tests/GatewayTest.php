<?php

namespace Omnipay\BarclaysEpdqDl;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\BarclaysEpdqDl\Message\PurchaseRequest;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {       
        $request = $this->gateway->purchase(
            [
                'transactionId' => 'xxxx-test-xxxxx',
                'amount' => '10.00',
                'currency' => 'GBP',
                'card' => [
                    'number' => '5399999999999999',
                    'expiryMonth' => '6',
                    'expiryYear' => '2020',
                    'cvv' => '123'
                ]
            ]
        );

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('GBP', $request->getCurrency());
        $this->assertSame('xxxx-test-xxxxx', $request->getTransactionId());
    }
}