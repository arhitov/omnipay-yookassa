<?php

namespace Omnipay\YooKassa\Tests\Feature;

use Omnipay\YooKassa\Tests\FeatureTestCase;
use YooKassa\Client;
use YooKassa\Common\Exceptions\NotFoundException;

/**
 * Get payment information.
 */
class DetailsTest extends FeatureTestCase
{

    /**
     * @return void
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ExtensionNotFoundException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
    public function testPaymentInfoBySDK()
    {
        $client = new Client();
        $client->setAuth($this->getConfig('shop_id'), $this->getConfig('secret_key'));

        // Test not found
        $paymentId = '11111111-1111-1111-1111-111111111111';
        try {
            $client->getPaymentInfo($paymentId);
            $find = true;
        } catch (NotFoundException) {
            $find = false;
        }
        $this->assertFalse($find);

        // Test fount
        // @TODO PASS...
    }

    /**
     * @depends testPaymentInfoBySDK
     * @return void
     */
    public function testPaymentInfoByGateway()
    {
        $this->markTestSkipped('The payment info test has no implementation!');
    }
}