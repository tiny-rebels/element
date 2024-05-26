<?php global $app;

use app\middleware\security\{
    back\AuthMiddleware,
    CsrfViewMiddleware
};

use Psr\Container\{
    ContainerExceptionInterface,
    NotFoundExceptionInterface
};

use Slim\{
    Csrf\Guard,
    Router,
    Views\Twig
};

/**
 * Pulling container items
 */
try {

    $guard  = $app->getContainer()->get(Guard::class);
    $router = $app->getContainer()->get(Router::class);
    $view   = $app->getContainer()->get(Twig::class);

} catch (NotFoundExceptionInterface|ContainerExceptionInterface $error) {

    // ...do something

}

/**
 * OUTER Group that applies CSRF to routes
 *
 * @var $app
 *
 */
$app->group('/user', function () use($app) {

    /**
     * rendering view : USER -> PROFILE
     */
    $app->get('/profile', ['app\controllers\UserController', 'getUserProfile'])->setName('user.profile');

})
    ->add(new AuthMiddleware($router))
    ->add(new CsrfViewMiddleware($view, $guard))
    ->add($guard)
;