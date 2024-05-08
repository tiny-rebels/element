<?php

namespace app\controllers;

use app\models\data\User;

use app\models\mail\auth\Welcome;

use Cartalyst\Sentinel\{
    Native\Facades\Sentinel,
    Checkpoints\NotActivatedException,
    Checkpoints\ThrottlingException
};

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};

use Respect\Validation\Validator as v;

class AuthController extends BaseController {

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function getSignIn(Response $response) {

        return $this->view->render($response, '/auth/sign-in.twig', [

            'setup'     => $this->appSetup,
            'locales'   => $this->locales,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response|\Slim\Http\Response|void
     */
    public function postSignIn(Request $request, Response $response) {

        $remember = (bool)$request->getParam('persist');

        try {

            $credentials = [

                'email'    => $request->getParam('email'),
                'password' => $request->getParam('password')
            ];

            Sentinel::authenticate($credentials, $remember);

        } catch (NotActivatedException $error) {

            /**
             * After generating a new token and saved that on the user,
             * we can then go ahead and send an email to the user...
             */
//            $user        = new User;
////        $user->name  = ucwords( $request->getParam('first_name'));
////        $user->email = $request->getParam('email');
//            $user->name  = "john";
//            $user->email = "stefan@korfitz.com";
//
//            $this->mailer->to($user->email, $user->name)->send(new Welcome($user, $this->translator));

            // redirecting the user if he's not activated
            return $response->withRedirect($this->router->pathFor('auth.activate-user'));

        } catch (ThrottlingException $error) {

            dump("suspect behavior from ip detected! Throttling user access");
            die;

        } catch (\Exception $error) {

            dump($error);
            die;

        }

        /**
         * After generating a new token and saved that on the user,
         * we can then go ahead and send an email to the user...
         */
        $user        = new User;
//        $user->name  = ucwords( $request->getParam('first_name'));
//        $user->email = $request->getParam('email');
        $user->name  = "john";
        $user->email = "stefan@korfitz.com";

        $this->mailer->to($user->email, $user->name)->send(new Welcome($user, $this->translator));

        return $response->withRedirect($this->router->pathFor('dashboard.overview'));
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function getSignUp(Response $response): Response {

        return $this->view->render($response, '/auth/sign-up.twig', [

            'setup'     => $this->appSetup,
            'locales'   => $this->locales,
        ]);
    }

    public function postSignUp(Request $request, Response $response) {

        // validations first

        try {

            // Register a new user
            Sentinel::register([

                'first_name'    => $request->getParam('first_name'),
                'last_name'     => $request->getParam('last_name'),
                'email'         => $request->getParam('email'),
                'password'      => $request->getParam('password'),

            ]);

        } catch (\Exception $error) {

            dump($error);
            die;

        }

        // TODO : should redirect to activation instead...
        return $response->withRedirect($this->router->pathFor('dashboard.overview'));
    }

    public function postSignOut(Request $request, Response $response) {

        try {

            Sentinel::logout();

        } catch (\Exception $error) {

            dump($error);
            die;

        }

        // redirect back to home view
        return $response->withRedirect($this->router->pathFor('home'));
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function getSystemLock(Response $response): Response {

        return $this->view->render($response, '/auth/lock-system.twig', [

            'locales'   => $this->locales,
        ]);
    }

    public function postSystemLock(Request $request, Response $response) {

        // ...do something
    }

    public function getActivation(Response $response) {

        return $this->view->render($response, '/auth/activation.twig', [

            'setup'     => $this->appSetup,
            'locales'   => $this->locales,
        ]);
    }

    public function postActivation(Request $request, Response $response) {

        /**
         * doing some basic validation BEFORE signup is executed
         */
        $validation = $this->validator->validate($request, [

            'input_1'    => v::notEmpty(),
            'input_2'    => v::notEmpty(),
            'input_3'    => v::notEmpty(),
            'input_5'    => v::notEmpty(),
            'input_6'    => v::notEmpty(),
            'input_7'    => v::notEmpty(),
        ]);

        dump($validation);
        die;

        /**
         * if validation fails, then we redirect the user to : /auth/lock-system
         */
        if ($validation->fails()) {

            return $response->withRedirect($this->router->pathFor('auth.activate.user-from-token'));
        }

        $token = $request->getParam('input_1') . $request->getParam('input_2') . $request->getParam('input_3') . "-" . $request->getParam('input_5') . $request->getParam('input_6') . $request->getParam('input_7');

        /**
         * attempt to signin...
         */
        $activate = $this->auth->activate(

            $token
        );

        /**
         * if signin FAILS, then we redirect back...
         */
        if (!$activate) {

            $this->flash->addMessage('danger', 'Hmm ?! Prøv igen...');

            return $response->withRedirect($this->router->pathFor('auth.activate.user-from-token'));
        }

        // TODO : check på om brugeren er en medarbejder? (med adgang til nexus) ellers skal der omdirigeres til airport

        $this->flash->addMessage('success', 'velkommen');

        return $response->withRedirect($this->router->pathFor('dashboard'));

    }

}