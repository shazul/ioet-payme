<?php

declare(strict_types=1);

namespace Payme;

define('BASE_DIR', __DIR__);

// The paths of classes that will be used in autoloading
$mapping = [
    'Payme\\Controllers\\' => BASE_DIR . '/controllers',
    'Payme\\Services\\' => BASE_DIR . '/services',
    'Payme\\Support\\' => BASE_DIR . '/support',
    'Payme\\Support\\Adapters\\' => BASE_DIR . '/support/adapters',
    'Payme\\Support\\Interfaces' => BASE_DIR . '/support/interfaces',
    'Payme\\Support\\Utils' => BASE_DIR . '/support/utils',
    'Payme\\Validators\\' => BASE_DIR . '/validators',
];

// Autoload the classes
spl_autoload_register(function ($class) use ($mapping) {
    foreach ($mapping as $namespace => $dir) {
        if (strpos($class, $namespace) === 0) {
            $file = $dir . '/' . str_replace('\\', '/', substr($class, strlen($namespace))) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});