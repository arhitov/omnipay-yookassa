<?php

namespace Omnipay\YooKassa\Tests\Fixtures;

use ErrorException;

trait FixtureTrait
{
    public function getShopId(): int
    {
        return 123; // 54401
    }

    public function getSecretKey(): string
    {
        return 'test_123456'; // test_Fh8hUAVVBGUGbjmlzba6TB0iyUbos_lueTHE-axOwM0
    }

    public function getTransactionId(): string
    {
        return '5ce3cdb0d1436';
    }
    public function getTransactionReference(): string
    {
        return '2475e163-000f-5000-9000-18030530d620';
    }

    public function getAmount(): int|float
    {
        return 187.50;
    }

    public function getCurrency(): string
    {
        return 'RUB';
    }

    public function getDescription(): string
    {
        return 'Test completePurchase description';
    }

    public function getCapture(): bool
    {
        return false;
    }

    public function getNotifyUrl(): string
    {
        return 'https://www.example.com/pay'; // gateway will send here a POST message
    }

    public function getReturnUrl(): string
    {
        return 'https://www.example.com/payment-success'; // buyer will be redirected here if purchase confirmed
    }

    public function getCancelUrl(): string
    {
        return 'https://www.example.com/payment-failure'; // buyer will be redirected here if purchase cancelled or rejected
    }

    public function getRedirectUrl(string $transactionReference): string
    {
        return "https://yoomoney.ru/checkout/payments/v2/contract?orderId={$transactionReference}";
    }

    public function getCard(): array
    {
        return [
            'number' => '5555555555554444',
            'expiryMonth' => '6',
            'expiryYear' => (int)(date('Y')) + 1,
            'cvv' => '123'
        ];
    }

    /**
     * @param string $name
     * @return string
     * @throws ErrorException
     */
    protected function fixture(string $name): string
    {
        $fixtureOmnipayYooKassa = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixture', '']) . $name . '.json';
        if (file_exists($fixtureOmnipayYooKassa)) {
            return file_get_contents($fixtureOmnipayYooKassa);
        }

        $nameExp = explode('/', $name);
        if (empty($nameExp[1])) {
            throw new ErrorException("Not found fixture \"{$name}\"");
        }

        $fixtureYooMoney = implode(
                DIRECTORY_SEPARATOR,
                [
                    $_SERVER['DOCUMENT_ROOT'],
                    'vendor',
                    'yoomoney',
                    'yookassa-sdk-php',
                    'tests',
                    $nameExp[0],
                    'fixtures',
                    '',
                ]) . $nameExp[1] . 'Fixtures.json';
        if (file_exists($fixtureYooMoney)) {
            return file_get_contents($fixtureYooMoney);
        }

        throw new ErrorException("Not found fixture \"{$name}\"");
    }

    /**
     * @param string $name
     * @return array
     * @throws ErrorException
     */
    protected function fixtureArray(string $name): array
    {
        return json_decode($this->fixture($name), true, JSON_UNESCAPED_UNICODE);
    }
}