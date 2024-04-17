<?php

namespace Omnipay\YooKassa\Tests\Feature;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Omnipay\YooKassa\Message\PurchaseResponse;
use Omnipay\YooKassa\Tests\FeatureTestCase;
use Omnipay\YooKassa\Tests\Fixtures\FixtureTrait;
use YooKassa\Client;

/**
 * Create a payment.
 */
class PurchaseTest extends FeatureTestCase
{
    use FixtureTrait;

    /**
     * @return void
     * @throws \YooKassa\Common\Exceptions\ApiConnectionException
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\AuthorizeException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ExtensionNotFoundException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\NotFoundException
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
    public function testCreatePaymentBySDK(): void
    {
        $client = new Client();
        $client->setAuth($this->getConfig('shop_id'), $this->getConfig('secret_key'));

        $amountValue = $this->getAmount();
        $amountCurrency = $this->getConfig('currency');
        $description = $this->getDescription();

        $payment = $client->createPayment(
            [
                'amount'       => [
                    'value'    => $amountValue,
                    'currency' => $amountCurrency,
                ],
                'confirmation' => [
                    'type'       => 'redirect',
                    'return_url' => $this->getReturnUrl(),
                ],
                'capture'      => true,
                'description'  => $description,
            ],
            uniqid('', true),
        );

        $this->assertSame($amountValue, (float)$payment->getAmount()->value);
        $this->assertSame($amountCurrency, $payment->getAmount()->currency);
        $this->assertSame('pending', $payment->getStatus());
        $this->assertFalse($payment->getPaid());
        $this->assertSame('redirect', $payment->getConfirmation()->getType());
        $this->assertNotEmpty($payment->getConfirmation()->getConfirmationUrl());
    }

    /**
     * @depends testCreatePaymentBySDK
     * @return void
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCreatePaymentByGateway()
    {
        $gateway = Omnipay::create('YooKassa');
        $gateway->setAuth($this->getConfig('shop_id'), $this->getConfig('secret_key'));

        $amountValue = $this->getAmount();
        $amountCurrency = $this->getConfig('currency');
        $transactionId = $this->getTransactionId();

        // Send purchase request
        /** @var ResponseInterface|PurchaseResponse $response */
        $response = $gateway->purchase(
            [
                'amount'        => $amountValue,
                'currency'      => $amountCurrency,
                'returnUrl'     => $this->getReturnUrl(),
                'transactionId' => $transactionId,
                'description'   => $this->getDescription(),
                'capture'       => true,
            ],
        )->send();

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNotEmpty($response->getRedirectUrl());
        $this->assertSame($transactionId, $response->getTransactionId());
        $this->assertSame($amountValue, (float)$response->getAmount());
        $this->assertSame($amountCurrency, $response->getCurrency());
        $this->assertSame('pending', $response->getState());
    }
}
