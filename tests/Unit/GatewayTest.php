<?php
/**
 * YooKassa driver for Omnipay payment processing library
 *
 * @link      https://github.com/arhitov/omnipay-yookassa
 * @package   omnipay-yookassa
 * @license   MIT
 * @copyright Copyright (c) 2021, Igor Tverdokhleb, igor-tv@mail.ru
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnipay\YooKassa\Tests\Unit;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\YooKassa\Gateway;
use Omnipay\YooKassa\Tests\Fixtures\FixtureTrait;

class GatewayTest extends GatewayTestCase
{
    use FixtureTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setShopId($this->getShopId());
        $this->gateway->setSecret($this->getSecretKey());
    }

    public function testGateway()
    {
        $this->assertSame($this->getShopId(), $this->gateway->getShopId());
        $this->assertSame($this->getSecretKey(), $this->gateway->getSecret());
    }

    /**
     * @depends testGateway
     * @return void
     */
    public function testPurchase()
    {
        $transactionId = $this->getTransactionId();
        $amount = $this->getAmount();
        $currency = $this->getCurrency();
        $description = $this->getDescription();
        $capture = $this->getCapture();

        $request = $this->gateway->purchase([
            'transactionId' => $transactionId,
            'amount'        => $amount,
            'currency'      => $currency,
            'description'   => $description,
            'capture'       => $capture,
        ]);

        $this->assertSame($transactionId, $request->getTransactionId());
        $this->assertSame($description, $request->getDescription());
        $this->assertSame($currency, $request->getCurrency());
        $this->assertSame($capture, $request->getCapture());
        $this->assertEquals($amount, $request->getAmount());
    }

    public function testCaptureParameters()
    {
        $this->markTestSkipped('The test has no implementation!');
    }

    public function testPurchaseParameters()
    {
        $this->markTestSkipped('The test has no implementation!');
    }

    public function testDefaultParametersHaveMatchingMethods()
    {
        $this->markTestSkipped('The test has no implementation!');
    }
}
