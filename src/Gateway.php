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

namespace Omnipay\YooKassa;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\YooKassa\Message\CaptureRequest;
use Omnipay\YooKassa\Message\CaptureResponse;
use Omnipay\YooKassa\Message\DetailsRequest;
use Omnipay\YooKassa\Message\DetailsResponse;
use Omnipay\YooKassa\Message\IncomingNotificationRequest;
use Omnipay\YooKassa\Message\PurchaseRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use YooKassa\Client;

/**
 * Class Gateway.
 */
class Gateway extends AbstractGateway
{
    private Client|null $yooKassaClient = null;

    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        parent::__construct($httpClient, $httpRequest);
    }

    protected function getYooKassaClient(): Client
    {
        if ($this->yooKassaClient === null) {
            $this->yooKassaClient = new Client();
            $this->yooKassaClient->setAuth($this->getParameter('shopId'), $this->getParameter('secret'));
        }

        return $this->yooKassaClient;
    }

    public function getName(): string
    {
        return 'YooKassa';
    }

    public function setAuth(string|int $shopId, string $secret): self
    {
        $this->setParameter('shopId', $shopId);
        $this->setParameter('secret', $secret);
        return $this;
    }

    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    public function setShopId($value): self
    {
        return $this->setParameter('shopId', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value): self
    {
        return $this->setParameter('secret', $value);
    }

    /**
     * Create a payment.
     *
     * @param array $options
     * @return PurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $options = []): AbstractRequest|PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $this->getParametersClient($options));
    }

    /**
     * Payment confirmation.
     *
     * @param array $options
     * @return CaptureResponse|\Omnipay\Common\Message\AbstractRequest
     */
    public function capture(array $options = []): AbstractRequest|CaptureResponse
    {
        return $this->createRequest(CaptureRequest::class, $this->getParametersClient($options));
    }

    /**
     * Get payment information.
     *
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|DetailsRequest
     */
    public function details(array $options = []): DetailsRequest|AbstractRequest
    {
        return $this->createRequest(DetailsRequest::class, $this->getParametersClient($options));
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|DetailsResponse
     */
    public function notification(array $options = []): DetailsResponse|AbstractRequest
    {
        return $this->createRequest(IncomingNotificationRequest::class, $this->getParametersClient($options));
    }

    private function getParametersClient(array $options): array
    {
//        $this->setShopId($this->parameters->get('shopId'));
//        $this->setSecret($this->parameters->get('secret'));
        $options['yooKassaClient'] = $this->getYooKassaClient();

        return $options;
    }
}
