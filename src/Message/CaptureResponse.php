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
use YooKassa\Model\Payment\PaymentStatus;
use YooKassa\Request\Payments\CreateCaptureResponse;

/**
 * Class CaptureResponse.
 *
 * @property CreateCaptureResponse $data
 */
class CaptureResponse extends DetailsResponse
{
    protected function ensureResponseIsValid(): void
    {
        parent::ensureResponseIsValid();

        if ($this->getState() !== PaymentStatus::SUCCEEDED) {
            throw new InvalidResponseException(sprintf('Failed to capture payment "%s"', $this->getTransactionReference()));
        }
    }
}
