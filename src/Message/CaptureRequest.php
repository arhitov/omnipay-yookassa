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

use Omnipay\Common\Exception\InvalidRequestException;
use Throwable;

/**
 * Payment confirmation.
 * Confirms your willingness to accept payment. At this stage, the money is reserved in the payer's account and awaits confirmation from the seller.
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('shopId', 'secret', 'transactionReference', 'amount', 'currency');

        return $this->httpRequest->request->all();
    }

    /**
     * @param mixed $data
     * @throws InvalidRequestException
     * @return \Omnipay\Common\Message\ResponseInterface|CaptureResponse
     */
    public function sendData($data)
    {
        try {
            $result = $this->client->capturePayment(
                [
                    'amount' => [
                        'value' => $this->getAmount(),
                        'currency' => $this->getCurrency(),
                    ],
                ],
                $this->getTransactionReference(),
                $this->makeIdempotencyKey(),
            );

            return $this->response = new CaptureResponse($this, $result);
        } catch (Throwable $e) {
            throw new InvalidRequestException('Failed to capture payment: ' . $e->getMessage(), 0, $e);
        }
    }

    private function makeIdempotencyKey(): string
    {
        return md5(
            implode(
                ',',
                [
                    'capture',
                    json_encode([
                        'amount'                => $this->getAmount(),
                        'currency'              => $this->getCurrency(),
                        'transaction_reference' => $this->getTransactionReference(),
                    ]),
                ],
            )
        );
    }
}
