<?php

namespace app\controllers;

use app\models\data\User;

use Element\Unique\Generate;

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};

class HomeController extends BaseController {

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function getHome(Request $request, Response $response): Response {

        $users  = User::paginate(3);

        dump(Generate::softwareLicenseKey());
        dump(Generate::twoFactorRecoveryCodes());

        return $this->view->render($response, '/home/index.twig', [

            'setup'     => $this->appSetup,
            'locales'   => $this->locales,

            'users'     => $users
        ]);
    }
}