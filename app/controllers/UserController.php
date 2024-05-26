<?php

namespace app\controllers;

use Cartalyst\Sentinel\{
    Native\Facades\Sentinel
};

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};

class UserController extends BaseController {

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function getUserProfile(Response $response): Response {

        return $this->view->render($response, '/dashboard/user-profile.twig', [

            'setup'         => $this->appSetup,
            'locales'       => $this->locales,

            'br_title'      => "User",
            'br_subtitle'   => "Profile",
        ]);
    }
}