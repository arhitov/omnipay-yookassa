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

namespace Omnipay\YooKassa\Tests;

use Omnipay\Tests\TestCase as OmnipayTestCase;
use Omnipay\YooKassa\Message\AbstractRequest;
use Omnipay\YooKassa\Tests\Fixtures\FixtureTrait;
use ReflectionObject;
use YooKassa\Client;

class UnitMessageTestCase extends OmnipayTestCase
{
    use FixtureTrait;

    /**
     * @param string $shopId
     * @param string $secretKey
     * @return \YooKassa\Client
     */
    protected function buildYooKassaClient(string $shopId, string $secretKey): Client
    {
        $client = new Client();
        $client->setAuth($shopId, $secretKey);

        return $client;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|(\YooKassa\Client\CurlClient&\PHPUnit\Framework\MockObject\MockObject)
     */
    protected function getCurlClientStub()
    {
        return $this->getMockBuilder(Client\CurlClient::class)
                    ->onlyMethods(['sendRequest'])
                    ->getMock();
    }

    /**
     * @param \Omnipay\YooKassa\Message\AbstractRequest $request
     * @return \YooKassa\Client
     * @throws \ReflectionException
     */
    protected function getYooKassaClient(AbstractRequest $request): Client
    {
        $clientReflection = (new ReflectionObject($request))->getProperty('client');
        $clientReflection->setAccessible(true);
        return $clientReflection->getValue($request);
    }
}
