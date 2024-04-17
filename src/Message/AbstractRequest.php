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

namespace Omnipay\YooKassa\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use YooKassa\Client;

/**
 * Class AbstractRequest.
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    protected Client $client;

    public function getShopId(): string|int
    {
        return $this->getParameter('shopId');
    }

    public function setShopId(string|int $value): self
    {
        return $this->setParameter('shopId', $value);
    }

    public function getSecret(): string
    {
        return $this->getParameter('secret');
    }

    public function setSecret(string $value): self
    {
        return $this->setParameter('secret', $value);
    }

    public function getCapture(): bool
    {
        return $this->getParameter('capture');
    }
    
    public function setCapture(bool $value): self
    {
        return $this->setParameter('capture', $value);
    }

    public function getReceipt()
    {
        return $this->getParameter('receipt');
    }
    
    public function setReceipt($value): self
    {
        return $this->setParameter('receipt', $value);
    }

    public function getPaymentMethodData()
    {
        return $this->getParameter('payment_method_data');
    }
    
    public function setPaymentMethodData($value): self
    {
        return $this->setParameter('payment_method_data', $value);
    }

    public function setYooKassaClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }
}
