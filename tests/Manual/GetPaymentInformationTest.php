<?php

namespace Omnipay\YooKassa\Tests\Manual;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Omnipay;
use Omnipay\YooKassa\Tests\FeatureTestCase;

class GetPaymentInformationTest extends FeatureTestCase
{
    public function testMain()
    {
        $transactionReference = self::readline('Please input transactionReference: ');

        /** @var \Omnipay\YooKassa\Gateway $gateway */
        $gateway = Omnipay::create('YooKassa');
        $gateway->setAuth($this->getConfig('shop_id'), $this->getConfig('secret_key'));

        // Send details request
        $details = $gateway->details([
            'transactionReference' => $transactionReference,
        ]);

        /** @var \Omnipay\Common\Message\ResponseInterface|\Omnipay\YooKassa\Message\DetailsResponse $response */
        try {
            $response = $details->send();
        } catch (InvalidResponseException $exception) {
            $this->fail($exception->getMessage());
        }


        echo PHP_EOL . PHP_EOL;
        echo 'Amount: ' . $response->getAmount() . ' ' . $response->getCurrency() . PHP_EOL;
        if ($response->isSuccessful() || $response->isWaitingForCapture()) {
            echo 'Payer: ' . $response->getPayer() . PHP_EOL;
        }
        echo 'isPending: ' . ($response->isPending() ? 'yes' : 'no') . PHP_EOL;
        echo 'isWaitingForCapture: ' . ($response->isWaitingForCapture() ? 'yes' : 'no') . PHP_EOL;
        echo 'isSuccessful: ' . ($response->isSuccessful() ? 'yes' : 'no') . PHP_EOL;
        echo 'isCancelled: ' . ($response->isCancelled() ? 'yes' : 'no') . PHP_EOL;
        echo 'State: ' . $response->getState() . PHP_EOL;

        $this->assertTrue(true);
    }

    public static function readline(?string $prompt): string
    {
        ob_get_flush();
        ob_start();
        return readline($prompt);
    }
}
