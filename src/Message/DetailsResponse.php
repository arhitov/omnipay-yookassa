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
use Omnipay\Common\Message\RequestInterface;
use YooKassa\Model\Payment\PaymentInterface;

/**
 * Class DetailsResponse.
 *
 * @property PaymentInterface $data
 */
class DetailsResponse extends AbstractResponse
{
    /**
     * @return RequestInterface|DetailsRequest
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    public function __construct(RequestInterface $request, PaymentInterface $payment)
    {
        parent::__construct($request, $payment);

        $this->ensureResponseIsValid();
    }

    protected function ensureResponseIsValid(): void
    {
        if ($this->getTransactionId() === null) {
            throw new InvalidResponseException(sprintf(
                'Transaction ID is missing in payment "%s"',
                $this->getTransactionReference()
            ));
        }
    }

    /**
     * Has payment been made?
     *
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data->paid;
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

    public function getPaymentDate(): \DateTime
    {
        return $this->data->getCreatedAt();
    }

    public function getTransactionReference(): string
    {
        return $this->data->getId();
    }

    public function getTransactionId(): ?string
    {
        return $this->data->getMetadata()['transactionId'] ?? null;
    }

    public function getState(): string
    {
        return $this->data->getStatus();
    }

    public function getPayer(): string
    {
        $method = $this->data->getPaymentMethod();

        return $method->getTitle() ?: $method->getType() ?: '';
    }
}
