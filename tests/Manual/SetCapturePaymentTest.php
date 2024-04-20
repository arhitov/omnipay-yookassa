<?php

namespace Omnipay\YooKassa\Tests\Manual;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Omnipay\YooKassa\Message\CaptureResponse;
use Omnipay\YooKassa\Message\DetailsResponse;
use Omnipay\YooKassa\Message\PurchaseResponse;
use Omnipay\YooKassa\Tests\FeatureTestCase;

class SetCapturePaymentTest extends FeatureTestCase
{
    public function testMain()
    {
        $amount = 123.45;
        $currency = 'RUB';

        /** @var \Omnipay\YooKassa\Gateway $gateway */
        $gateway = Omnipay::create('YooKassa');
        $gateway->setAuth($this->getConfig('shop_id'), $this->getConfig('secret_key'));

        // Send purchase request
        /** @var ResponseInterface|PurchaseResponse $response */
        $response = $gateway->purchase([
            'amount'        => $amount,
            'currency'      => $currency,
            'returnUrl'     => 'https://www.example.com/pay',
            'transactionId' => time(),
            'description'   => 'Test payment',
            'capture'       => false,
        ])->send();

        $this->printPaymentInformation($response);

        $transactionReference = $response->getTransactionReference();

        $this->assertEquals('pending', $response->getState());
        $this->assertTrue($response->isPending());

        echo 'Please follow the link and pay the invoice.' . PHP_EOL;

        do {
            readline('After payment, press Enter.');

            // Send details request
            $details = $gateway->details([
                'transactionReference' => $transactionReference,
            ]);

            /** @var ResponseInterface|DetailsResponse $response */
            try {
                $response = $details->send();
            } catch (InvalidResponseException $exception) {
                $this->fail($exception->getMessage());
            }

            $this->printPaymentInformation($response);

        } while (! $response->isSuccessful());

        $this->assertEquals('waiting_for_capture', $response->getState());
        $this->assertTrue($response->isWaitingForCapture());

        // Send capture request
        $capture = $gateway->capture([
            'transactionReference' => $transactionReference,
            'amount' => $amount,
            'currency' => $currency,
        ]);

        /** @var ResponseInterface|CaptureResponse $response */
        try {
            $response = $capture->send();
        } catch (InvalidResponseException $exception) {
            $this->fail($exception->getMessage());
        }

        $this->printPaymentInformation($response);

        $this->assertTrue(true);
    }

    public function printPaymentInformation(ResponseInterface|PurchaseResponse|DetailsResponse|CaptureResponse $response): void
    {
        echo PHP_EOL . PHP_EOL;
        echo 'Created payment: ' . $response->getTransactionReference() . PHP_EOL;
        echo 'Amount: ' . $response->getAmount() . ' ' . $response->getCurrency() . PHP_EOL;
        if ($response->isSuccessful() || $response->isWaitingForCapture()) {
            echo 'Payer: ' . $response->getPayer() . PHP_EOL;
        }
        echo 'isPending: ' . ($response->isPending() ? 'yes' : 'no') . PHP_EOL;
        echo 'isWaitingForCapture: ' . ($response->isWaitingForCapture() ? 'yes' : 'no') . PHP_EOL;
        echo 'isSuccessful: ' . ($response->isSuccessful() ? 'yes' : 'no') . PHP_EOL;
        echo 'isCancelled: ' . ($response->isCancelled() ? 'yes' : 'no') . PHP_EOL;
        if ($response instanceof PurchaseResponse) {
            echo 'isRedirect: ' . ($response->isRedirect() ? 'yes' : 'no') . PHP_EOL;
            echo 'getRedirectUrl: ' . $response->getRedirectUrl() . PHP_EOL;
        }
        echo 'getState: ' . $response->getState() . PHP_EOL . PHP_EOL;
        ob_get_flush();
        ob_start();
    }

    public static function readline(?string $prompt): string
    {
        ob_get_flush();
        ob_start();
        echo PHP_EOL . PHP_EOL;
        return readline($prompt);
    }
}
