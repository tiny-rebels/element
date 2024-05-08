<?php

namespace app\controllers;

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};

class SetupController extends BaseController {

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response|\Slim\Http\Response
     */
    public function switchAppColor(Request $request, Response $response) {

        $this->appSetup->update([

            'app_color' => $request->getParam('app_color')
        ]);

        return $response->withRedirect($this->router->pathFor('dashboard.overview')); // TODO : Should be replaced with last url
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response|\Slim\Http\Response
     */
    public function switchSidebarType(Request $request, Response $response) {

        $this->appSetup->update([

            'app_sidebar_type' => $request->getParam('sidebar_type')
        ]);

        return $response->withRedirect($this->router->pathFor('dashboard.overview')); // TODO : Should be replaced with last url
    }

    /**
     * @param Response $response
     *
     * @return Response|\Slim\Http\Response
     */
    public function toggleNavbarFixed(Response $response) {

        if ($_POST['navbarFixed'] == "on") {

            $this->appSetup->update([

                'app_navbar_fixed' => 1
            ]);

        } else {

            $this->appSetup->update([

                'app_navbar_fixed' => 0
            ]);

        }

        return $response->withRedirect($this->router->pathFor('dashboard.overview')); // TODO : Should be replaced with last url
    }
}