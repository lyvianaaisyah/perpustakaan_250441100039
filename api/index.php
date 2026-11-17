
<?php

$key = getenv('APP_KEY');
$keyServer = $_SERVER['APP_KEY'] ?? null;
$keyEnv = $_ENV['APP_KEY'] ?? null;

$diagnostic = [
    'getenv' => is_string($key) && $key !== '',
    '_SERVER' => is_string($keyServer) && $keyServer !== '',
    '_ENV' => is_string($keyEnv) && $keyEnv !== '',
];

error_log(
    'APP_KEY_DIAGNOSTIC: ' . json_encode($diagnostic)
);

if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

require __DIR__ . '/../public/index.php';
