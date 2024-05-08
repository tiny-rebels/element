<?php

//use app\middleware\auth\CsrfViewMiddleware;

//use Slim\{
//    Csrf\Guard,
//    Views\Twig
//};

/**
 * OUTER Group that applies CSRF to routes
 *
 * @var $app
 * @var $container
 *
 */
$app->group('', function () use($app, $container) {

    /**
     * rendering view : HOME
     */
    $app->get('/', ['app\controllers\HomeController', 'getHome'])->setName('home');

//})->add(new CsrfViewMiddleware($container->get(Twig::class), $container->get(Guard::class)))->add($container->get(Guard::class));
});
