<?php
use Silex\Application;

$app = new Application();
$app['debug'] = true;

require 'services.php';
require 'controllers.php';
require 'routes.php';

return $app;
