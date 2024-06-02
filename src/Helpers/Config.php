<?php

namespace App\Helpers;

class Config
{
    private static ?array $config = null;

    public static function get($key, $default = null) {
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../../config.php';
        }

        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return $default;
            }

            $value = $value[$key];
        }

        return $value;
    }
}
