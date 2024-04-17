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
use Omnipay\YooKassa\Tests\UnitMessageTestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class IncomingNotificationResponseUnitMessageTest extends UnitMessageTestCase
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
    public function testSuccess()
    {
        $response = $this->request->send();

        $fixtureArray = $this->fixtureArray(self::FIXTURE);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($fixtureArray['object']['metadata']['transactionId'], $response->getTransactionId());
        $this->assertSame($fixtureArray['object']['id'], $response->getTransactionReference());
    }
}
