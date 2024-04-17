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

use Omnipay\YooKassa\Message\DetailsRequest;
use Omnipay\YooKassa\Message\DetailsResponse;
use Omnipay\YooKassa\Tests\UnitMessageTestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class DetailsRequestUnitMessageTest extends UnitMessageTestCase
{

    const FIXTURE = 'payment.waiting_for_capture';

    private DetailsRequest $request;

    /**
     * @return void
     * @throws \ErrorException
     */
    public function setUp(): void
    {
        parent::setUp();

        $fixtureArray = $this->fixtureArray(self::FIXTURE);

        $httpRequest = new HttpRequest();
        $this->request = new DetailsRequest($this->getHttpClient(), $httpRequest);
        $this->request->initialize([
            'yooKassaClient'       => $this->buildYooKassaClient($this->getShopId(), $this->getSecretKey()),
            'shopId'               => $this->getShopId(),
            'secret'               => $this->getSecretKey(),
            'transactionReference' => $fixtureArray['id'],
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertEmpty($data);
    }

    /**
     * @depends testGetData
     * @return void
     * @throws \ErrorException
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     * @throws \ReflectionException
     */
    public function testSendData()
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

        $response = $this->request->sendData([]);
        $this->assertInstanceOf(DetailsResponse::class, $response);
    }
}
