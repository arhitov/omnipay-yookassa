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

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use YooKassa\Model\Payment\Confirmation\ConfirmationRedirect;
use YooKassa\Request\Payments\CreatePaymentResponse;

/**
 * Class PurchaseResponse.
 *
 * @property CreatePaymentResponse $data
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function getRedirectUrl()
    {
        $confirmation = $this->data->getConfirmation();
        if (!$confirmation instanceof ConfirmationRedirect) {
            throw new InvalidResponseException('Only redirect confirmation is supported');
        }

        return $confirmation->getConfirmationUrl();
    }

    public function getTransactionReference()
    {
        return $this->data->getId();
    }

    public function getTransactionId()
    {
        return $this->data->getMetadata()['transactionId'] ?? null;
    }

    public function isSuccessful()
    {
        return  $this->getState() === 'succeeded';
    }

    public function isPending(): bool
    {
        return $this->getState() === 'pending';
    }

    public function isCancelled(): bool
    {
        return $this->getState() === 'canceled';
    }

    public function isWaitingForCapture(): bool
    {
        return $this->getState() === 'waiting_for_capture';
    }

    public function getAmount(): string
    {
        return $this->data->getAmount()->getValue();
    }

    public function getCurrency(): string
    {
        return $this->data->getAmount()->getCurrency();
    }

    public function isRedirect()
    {
        return !$this->isSuccessful();
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return [];
    }

    public function getState(): string
    {
        return $this->data->getStatus();
    }
}
