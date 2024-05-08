<?php

namespace api\controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class AuthController extends BaseController {

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response|void
     */
    public function fetchToken(Request $request, Response $response) {

        //dump("it works from api auth controller");
        //die;

        $email      = $request->getParam('email');
        $password   = $request->getParam('password');

        $token      = $this->jwt->attempt($email, $password);

        if (!$token) {

            $data['error'] = 'Fetching Token failed';
            return $response->withJson($data, 401);
        }

        return $response->withJson([

            'token' => $token
        ]);
    }
}
