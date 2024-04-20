<?php

namespace Omnipay\YooKassa\Tests\Manual;

use Omnipay\Omnipay;
use Omnipay\YooKassa\Tests\FeatureTestCase;

class CreatePaymentTest extends FeatureTestCase
{
    public function testMain()
    {
        /** @var \Omnipay\YooKassa\Gateway $gateway */
        $gateway = Omnipay::create('YooKassa');
        $gateway->setAuth($this->getConfig('shop_id'), $this->getConfig('secret_key'));

        // Send purchase request
        /** @var \Omnipay\Common\Message\ResponseInterface|\Omnipay\YooKassa\Message\PurchaseResponse $response */
        $response = $gateway->purchase([
            'amount'        => 123.45,
            'currency'      => 'RUB',
            'returnUrl'     => 'https://www.example.com/pay',
            'transactionId' => time(),
            'description'   => 'Test payment',
            'capture'       => true,
        ])->send();

        echo PHP_EOL . PHP_EOL;
        echo 'isPending: ' . ($response->isPending() ? 'yes' : 'no') . PHP_EOL;
        echo 'isWaitingForCapture: ' . ($response->isWaitingForCapture() ? 'yes' : 'no') . PHP_EOL;
        echo 'isSuccessful: ' . ($response->isSuccessful() ? 'yes' : 'no') . PHP_EOL;
        echo 'isCancelled: ' . ($response->isCancelled() ? 'yes' : 'no') . PHP_EOL;
        echo 'isRedirect: ' . ($response->isRedirect() ? 'yes' : 'no') . PHP_EOL;
        echo 'getRedirectUrl: ' . $response->getRedirectUrl() . PHP_EOL;
        echo 'getState: ' . $response->getState() . PHP_EOL;

        $this->assertTrue(true);
    }
}
