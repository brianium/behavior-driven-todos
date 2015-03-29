<?php
use Peridot\Plugin\Prophecy\ProphecyPlugin;
use Peridot\Plugin\HttpKernel\HttpKernelPlugin;

return function($emitter) {
    $prophecy = new ProphecyPlugin($emitter);

    //set up app tests
    $app = include __DIR__ . '/app/app.php';
    $app['debug'] = true;
    $app['exception_handler']->disable();
    $app['todos-collection'] = $app['mongo-client']->test->todos;
    HttpKernelPlugin::register($emitter, $app);
};
