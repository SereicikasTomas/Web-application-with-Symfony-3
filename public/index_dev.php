<?php

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true) || PHP_SAPI === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'dev';
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = '1';

require __DIR__.'/index.php';
