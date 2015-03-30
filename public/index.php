<?php
// serve static files
$filename = __DIR__. preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require __DIR__ . '/../vendor/autoload.php';
$app = include __DIR__ . '/../app/app.php';
$app->run();
