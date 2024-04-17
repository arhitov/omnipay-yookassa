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

use Omnipay\YooKassa\Message\PurchaseRequest;
use Omnipay\YooKassa\Message\PurchaseResponse;
use Omnipay\YooKassa\Tests\UnitMessageTestCase;

class PurchaseRequestUnitMessageTest extends UnitMessageTestCase
{

    const FIXTURE = 'payment.pending';

    private PurchaseRequest $request;
    private array $fixtureArray;

    /**
     * @return void
     * @throws \ErrorException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->fixtureArray = $this->fixtureArray(self::FIXTURE);

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'yooKassaClient' => $this->buildYooKassaClient($this->getShopId(), $this->getSecretKey()),
            'transactionId'  => $this->fixtureArray['metadata']['transactionId'],
            'amount'         => $this->fixtureArray['amount']['value'],
            'currency'       => $this->fixtureArray['amount']['currency'],
            'description'    => $this->fixtureArray['description'],
            'returnUrl'      => $this->fixtureArray['confirmation']['return_url'],
            'refundable'     => true,
            'capture'        => $this->getCapture(),
        ]);
    }

    /**
     * @return void
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->fixtureArray['amount']['value'], $data['amount']);
        $this->assertSame($this->fixtureArray['amount']['currency'], $data['currency']);
        $this->assertSame($this->fixtureArray['description'], $data['description']);
        $this->assertSame($this->fixtureArray['confirmation']['return_url'], $data['return_url']);
        $this->assertSame($this->getCapture(), $data['capture']);
        $this->assertSame($this->fixtureArray['metadata']['transactionId'], $data['transactionId']);
    }

    /**
     * @depends testGetData
     * @return void
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function testSendData()
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub->method('sendRequest')->willReturn([
            [],
            $this->fixture(self::FIXTURE),
            ['http_code' => 200],
        ]);

        $this->getYooKassaClient($this->request)
             ->setApiClient($curlClientStub)
             ->setAuth($this->getShopId(), $this->getSecretKey());

        $response = $this->request->send();
        $this->assertInstanceOf(PurchaseResponse::class, $response);
    }
}
