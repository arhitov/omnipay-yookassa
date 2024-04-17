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

use Omnipay\YooKassa\Message\IncomingNotificationRequest;
use Omnipay\YooKassa\Message\IncomingNotificationResponse;
use Omnipay\YooKassa\Tests\UnitMessageTestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class IncomingNotificationRequestUnitMessageTest extends UnitMessageTestCase
{

    const FIXTURE = 'notification.payment.waiting_for_capture';

    private IncomingNotificationRequest $request;

    /**
     * @return void
     * @throws \ErrorException
     */
    public function setUp(): void
    {
        parent::setUp();

        $httpRequest = new HttpRequest(content: $this->fixture(self::FIXTURE));

        $this->request = new IncomingNotificationRequest($this->getHttpClient(), $httpRequest);
        $this->request->initialize([
            'shopId' => $this->getShopId(),
            'secret' => $this->getSecretKey(),
        ]);
    }

    /**
     * @return void
     * @throws \ErrorException
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->fixtureArray(self::FIXTURE), $data);
    }

    /**
     * @depends testGetData
     * @return void
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);

        $this->assertInstanceOf(IncomingNotificationResponse::class, $response);
    }
}
