<?php

namespace app\controllers;

use app\validations\contracts\ValidatorInterface;

use app\handlers\{
    mailer\Mailer
};

use app\models\{
    data\Locale,
    data\Setup
};

use Illuminate\Translation\Translator;

use Interop\Container\ContainerInterface;

use Noodlehaus\Config;
use Slim\{
    Router,
    Views\Twig
};

/**
 * @property \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null $appSetup
 * @property \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection $locales
 */
class BaseController {

    /**
     * BaseController dependencies
     */
    protected Config $config;
    protected Mailer $mailer;
    protected Router $router;
    protected Translator $translator;
    protected Twig $view;
    protected ContainerInterface $container;
    protected ValidatorInterface $validator;

    /**
     * BaseController constructor.
     *
     * @param ContainerInterface $container
     * @param Config $config
     * @param Mailer $mailer
     * @param Translator $translator
     * @param Router $router
     * @param Twig $view
     * @param ValidatorInterface $validator
     */
    public function __construct(ContainerInterface $container, Config $config, Mailer $mailer, Router $router, Translator $translator, ValidatorInterface $validator, Twig $view) {

        /*
         * NOTE!
         * Please visit the README.md file
         * at the root, for more info about
         * available app setup options...
         */
        $this->appSetup     = Setup::with([])->first();

        $this->locales      = Locale::with([])->where('activated', '=', true)->get();

        $this->container    = $container;
        $this->config       = $config;
        $this->mailer       = $mailer;
        $this->router       = $router;
        $this->translator   = $translator;
        $this->validator    = $validator;
        $this->view         = $view;
    }

    /**
     * @param $property
     *
     * @return mixed
     */
    public function __get($property) {

        if($this->container->{$property}) {

            return $this->container->{$property};

        } else {

            return null;

        }
    }
}
