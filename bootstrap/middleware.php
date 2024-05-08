<?php global $app;

use app\middleware\{
    validations\ErrorsMiddleware
};

use Slim\Views\Twig;

/**
 * Adding view to our database connection
 */
$view = $app->getContainer()->get(Twig::class);

/**
 * setting up : MIDDLEWARE
 */
/* -> CURRENT ROUTE */
//$app->add(new CurrentRouteMiddleware());
/* -> OLD INPUT */
//$app->add(new OldInputMiddleware($container->get(Twig::class)));
/* -> VALIDATION */
$app->add(new ErrorsMiddleware($view));