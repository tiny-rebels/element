<?php

namespace app\controllers;

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};

class DashboardController extends BaseController {

    public function getDashboardOverview(Response $response) {

        return $this->view->render($response, '/dashboard/overview.twig', [

            'setup'         => $this->appSetup,
            'locales'       => $this->locales,

            'br_title'      => "Dashboard",
            'br_subtitle'   => "Overview",
        ]);
    }
}