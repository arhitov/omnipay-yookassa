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

namespace Omnipay\YooKassa\Tests\Unit\Message;

use Carbon\Carbon;
use Omnipay\YooKassa\Message\CaptureRequest;
use Omnipay\YooKassa\Message\DetailsResponse;
use Omnipay\YooKassa\Message\IncomingNotificationRequest;
use Omnipay\YooKassa\Tests\UnitMessageTestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use const DATE_ATOM;

class CaptureResponseUnitMessageTest extends UnitMessageTestCase
{

    const FIXTURE = 'payment.succeeded';

    private CaptureRequest|IncomingNotificationRequest $request;
    private array $fixtureArray;

    /**
     * @return void
     * @throws \ErrorException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->fixtureArray = $this->fixtureArray(self::FIXTURE);

        $httpRequest = new HttpRequest();
        $this->request = new CaptureRequest($this->getHttpClient(), $httpRequest);
        $this->request->initialize([
            'yooKassaClient'       => $this->buildYooKassaClient($this->getShopId(), $this->getSecretKey()),
            'shopId'               => $this->getShopId(),
            'secret'               => $this->getSecretKey(),
            'transactionReference' => $this->fixtureArray['id'],
            'transactionId'        => $this->fixtureArray['metadata']['transactionId'],
            'amount'               => $this->fixtureArray['amount']['value'],
            'currency'             => $this->fixtureArray['amount']['currency'],
        ]);
    }

    /**
     * @return void
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function testSuccess(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub->method('sendRequest')
                       ->willReturn([
                           [],
                           $this->fixture(self::FIXTURE),
                           ['http_code' => 200],
                       ]);

        $this->getYooKassaClient($this->request)
             ->setApiClient($curlClientStub)
             ->setAuth($this->getShopId(), $this->getSecretKey());

        /** @var DetailsResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(DetailsResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame($this->fixtureArray['metadata']['transactionId'], $response->getTransactionId());
        $this->assertSame($this->fixtureArray['id'], $response->getTransactionReference());
        $this->assertSame($this->fixtureArray['amount']['value'], $response->getAmount());
        $this->assertSame($this->fixtureArray['amount']['currency'], $response->getCurrency());
        $this->assertSame(
            Carbon::parse($this->fixtureArray['created_at'])->format(DATE_ATOM),
            $response->getPaymentDate()->format(DATE_ATOM)
        );
        $this->assertSame('succeeded', $response->getState());
        $this->assertSame($this->fixtureArray['payment_method']['title'], $response->getPayer());
    }
}
