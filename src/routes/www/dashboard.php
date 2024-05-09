<?php global $app;

use app\middleware\security\{
    back\AuthMiddleware,
    CsrfViewMiddleware
};

use Slim\{
    Csrf\Guard,
    Router,
    Views\Twig
};

/**
 * Pulling container items
 */
$guard  = $app->getContainer()->get(Guard::class);
$router = $app->getContainer()->get(Router::class);
$view   = $app->getContainer()->get(Twig::class);

/**
 * OUTER Group that applies CSRF to routes
 *
 * @var $app
 *
 */
$app->group('/dashboard', function () use($app) {

    /**
     * rendering view : DASHBOARD -> OVERVIEW
     */
    $app->get('/overview', ['app\controllers\DashboardController', 'getDashboardOverview'])->setName('dashboard.overview');

})
    ->add(new AuthMiddleware($router))
    ->add(new CsrfViewMiddleware($view, $guard))
    ->add($guard)
;