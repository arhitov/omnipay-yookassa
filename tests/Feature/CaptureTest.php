<?php

namespace Omnipay\YooKassa\Tests\Feature;

use Omnipay\YooKassa\Tests\FeatureTestCase;

/**
 * Payment confirmation.
 */
class CaptureTest extends FeatureTestCase
{

    /**
     * @return void
     */
    public function testCapturePaymentBySDK(): void
    {
//        $client = new \YooKassa\Client();
//        $client->setAuth($this->getConfig('shop_id'), $this->getConfig('secret_key'));
//        $client->capturePayment(
        $this->markTestSkipped('The capture test has no implementation!');
    }

    /**
     * @depends testCapturePaymentBySDK
     * @return void
     */
    public function testCapturePaymentByGateway()
    {
//        $gateway = \Omnipay\Omnipay::create('YooKassa');
//        $gateway->setAuth($this->getConfig('shop_id'), $this->getConfig('secret_key'));
//        $response = $gateway->capture(
        $this->markTestSkipped('The capture test has no implementation!');
    }
}