<?php

namespace app\controllers;

use app\models\data\User;
use Illuminate\Pagination\LengthAwarePaginator;
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
        //$users  = User::with([])->get();

        //dump($users);

        return $this->view->render($response, '/home/index.twig', [

            'setup'     => $this->appSetup,
            'locales'   => $this->locales,

            'users'     => $users
        ]);
    }
}