<?php

$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');

if (! function_exists('dump')) {
    function dump(): void
    {
        $backtraceList = debug_backtrace(limit: 2);
        do {
            $backtrace = $backtraceList[0];
            [
                'file' => $file,
                'line' => $line,
            ] = $backtrace ?? ['file' => 'undefined', 'line' => 'undefined'];
            $backtraceList = array_slice($backtraceList, 1);
        } while ($file === __FILE__);
        echo "\n" . $file . ':' . $line . "\n";

        foreach (func_get_args() as $arg) {
            var_dump($arg);
            echo "\n";
        }
    }
}

if (! function_exists('dd')) {
    function dd(): void
    {
        dump(...func_get_args());
        exit;
    }
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function env(string $key, mixed $default = null): mixed
    {
        $env = parse_ini_file(__DIR__ . '/../.env');

        $value = $env[$key] ?? getenv($key);
        if (false === $value) {
            return $default;
        }

        return match ($value) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'empty', '(empty)' => '',
            'null', '(null)' => null,
            default => (function() use ($value) {
                $value = preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)
                    ? $matches[2]
                    : $value;

                if (is_numeric($value)) {
                    return str_contains($value, '.')
                        ? (float)$value
                        : (int)$value;
                }

                return $value;
            })(),
        };
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
