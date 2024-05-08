<?php

use function DI\get;

use app\handlers\{
    errors\NotFoundHandler,
    mailer\Mailer
};

use app\handlers\auth\{
    JwtAuth,
    JwtClaims,
    JwtFactory,
    Parser
};

use app\providers\{
    auth\EloquentProvider,
    jwt\FirebaseProvider
};

use app\validations\{
    Validator,
    contracts\ValidatorInterface
};

use app\views\{
    Factory,
    extensions\GetEnvExtension,
    extensions\GetYamlExtension,
    extensions\TranslationExtension
};

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use Illuminate\{
    Translation\Translator,
    Translation\FileLoader,
    Filesystem\Filesystem
};

use Noodlehaus\Config;

use Psr\Container\{
    ContainerInterface
};

use Slim\{
    Csrf\Guard,
    Router,
    Views\Twig,
    Views\TwigExtension
};

use Twig\Extra\Intl\IntlExtension;

/**
 * attaching : TO CONTAINER ->
 */
return [

    /* 404 ERROR */
    'notFoundHandler' => function(ContainerInterface $container) {

        return new NotFoundHandler ($container->get(Twig::class));
    },

    /* APP -> CONFIGS */
    Config::class => function() {

        switch ($_ENV["APP_MODE"]) {

            case 'development':
                return new Config(
                    __DIR__ . '/../config/development'
                );

            case 'production':
                return new Config(
                    __DIR__ . '/../config/production'
                );

            default:
                return new Config(
                    __DIR__ . '/../config/_examples'
                );
        }
    },

    /* AUTH -> JWT */
    JwtAuth::class => function (ContainerInterface $container) {

        $authProvider = new EloquentProvider();
        $claimsFactory = new JwtClaims(

            $container->get(ContainerInterface::class),
            $container->get('request')
        );

        $jwtProvider = new FirebaseProvider($container->get(ContainerInterface::class));

        $factory = new JwtFactory($claimsFactory, $jwtProvider);

        $parser = new Parser($jwtProvider);

        return new JwtAuth($authProvider, $factory, $parser);
    },

    /* CSRF */
    Guard::class => function () {

        $guard = new Guard();
        $guard->setPersistentTokenMode(true);

        return $guard;
    },

    /* MAILER */
    Mailer::class => function (ContainerInterface $container) {

        $transport = (new Swift_SmtpTransport($container->get(Config::class)->get('service.ms.host'), $container->get(Config::class)->get('service.ms.port'), 'tls'))
            ->setUsername($container->get(Config::class)->get('service.ms.username'))
            ->setPassword($container->get(Config::class)->get('service.ms.password'))
            ->setStreamOptions(array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false)));

        $swift = (new Swift_Mailer($transport));

        return (new Mailer($swift, $container->get(Twig::class)))
            ->alwaysFrom($container->get(Config::class)->get('service.ms.from.address'));
    },

    /* ROUTER */
    'router' => get(Router::class),

    /* TRANSLATOR */
    Translator::class => function (ContainerInterface $container) {

        $fallback = $container->get(Config::class)->get('i18n.translations.fallback');

        $loader = new FileLoader(
            new Filesystem(), $container->get(Config::class)->get('i18n.translations.path')
        );

        $translator = new Translator($loader, $_SESSION['lang'] ?? $fallback);
        $translator->setFallback($fallback);

        return $translator;
    },

    /* TWIG */
    Twig::class => function (ContainerInterface $container) {

        $config = $container->get(Config::class);

        $twig = Factory::getEngine($config);

        /* EXTENSIONS */
        $twig->addExtension(new TwigExtension(

            $container->get('router'),
            $container->get('request')->getUri()
        ));

        $twig->addExtension(new GetEnvExtension());

        $twig->addExtension(new IntlExtension());

        $twig->addExtension(new TranslationExtension(
            $container->get(Translator::class)
        ));

        /* GLOBALS */
        $twig->getEnvironment()->addGlobal('session', $_SESSION);

        $twig->getEnvironment()->addGlobal('current_path', $container->get('request')->getUri());

        $twig->getEnvironment()->addGlobal('auth', [

            'user' => Sentinel::check(),
        ]);

        return $twig;
    },

    /* VALIDATOR */
    ValidatorInterface::class => function () {

        return new Validator;
    },

];
