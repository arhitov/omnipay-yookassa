<?php

namespace Omnipay\YooKassa\Tests\Feature;

use Omnipay\YooKassa\Tests\FeatureTestCase;

/**
 * Payment confirmation.
 */
class CaptureTest extends FeatureTestCase
{

    /**
     * @depends testCapturePaymentBySDK
     * @return void
     */
    public function testCapturePaymentByGateway()
    {
        $this->markTestSkipped('This test can only be performed in manual mode. The test is here: tests/Manual/SetCapturePaymentTest');
    }
}