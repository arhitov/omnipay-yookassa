<?php

namespace Omnipay\YooKassa\Tests\Fixtures;

trait ConfigTrait
{
    private static array|null $config = null;

    public function getConfig(string $key = null)
    {
        self::initConfig();
        if ($key) {
            $value = self::$config;
            foreach (explode('.', $key) as $keyPart) {
                if (is_array($value) && array_key_exists($keyPart, $value)) {
                    $value = $value[$keyPart];
                } else {
                    return null;
                }
            }
            return $value;
        }
        return self::$config;
    }

    private static function initConfig()
    {
        if (is_null(self::$config)) {
            self::$config = require __DIR__ . '/../../config/omnipay_yookassa.php';
        }
    }
}