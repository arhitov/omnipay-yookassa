<?php
/**
 * YooKassa driver for Omnipay payment processing library
 *
 * @link      https://github.com/arhitov/omnipay-yookassa
 * @package   omnipay-yookassa
 * @license   MIT
 * @copyright Copyright (c) 2021, Igor Tverdokhleb
 * @copyright Copyright (c) 2024, Alexander Arhitov, clgsru@gmail.com
 */

namespace Omnipay\YooKassa\Tests\Unit\Message;

use Omnipay\YooKassa\Message\CaptureRequest;
use Omnipay\YooKassa\Message\CaptureResponse;
use Omnipay\YooKassa\Tests\UnitMessageTestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CaptureRequestUnitMessageTest extends UnitMessageTestCase
{

    const FIXTURE = 'payment.succeeded';

    private CaptureRequest $request;

    /**
     * @return void
     * @throws \ErrorException
     */
    public function setUp(): void
    {
        parent::setUp();

        $fixtureArray = $this->fixtureArray(self::FIXTURE);

        $this->request = new CaptureRequest(
            $this->getHttpClient(),
            new HttpRequest(),
        );
        $this->request->initialize([
            'yooKassaClient'       => $this->buildYooKassaClient($this->getShopId(), $this->getSecretKey()),
            'shopId'               => $this->getShopId(),
            'secret'               => $this->getSecretKey(),
            'transactionReference' => $fixtureArray['id'],
            'transactionId'        => $fixtureArray['metadata']['transactionId'],
            'amount'               => $fixtureArray['amount']['value'],
            'currency'             => $fixtureArray['amount']['currency'],
        ]);
    }

    public function testParameters()
    {
        $this->assertEquals($this->getShopId(), $this->request->getShopId());
        $this->assertEquals($this->getSecretKey(), $this->request->getSecret());
    }

    /**
     * @depends testParameters
     * @return void
     */
    public function testGetData()
    {
        $this->assertEmpty($this->request->getData());
    }

    /**
     * @depends testGetData
     * @return void
     * @throws \Omnipay\Common\Exception\InvalidRequestException
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

        $response = $this->request->sendData([]);

        $this->assertInstanceOf(CaptureResponse::class, $response);
    }
}
