<?php

/**
 * POZOR, tento controller nie je oddedeny z Abstract controllera, lebo tam sa vykonava kontrola prihlaseneho usera
 * a nepresiel by tade prihlasovaci POST request
 *
 * Class Admin_ControllerLogin
 */
class Projecto_ControllerLogin extends Controller
{

    public function run() {

        if (Request::isPost()) {
            $postData = Request::getPostData();

            if ($postData['formName'] == 'login') {
                $User = new Projecto_ModelUser();

                if ($User->login($postData)) {
                    Request::redirect(Request::makeUriAbsolute()); // default home page
                } else {
                    Request::redirect(Request::makeUriAbsolute('login'));
                }
            }
        }

        if (isset($_SESSION['isLoggedIn']) && ($_SESSION['isLoggedIn'] === true)) {
            Request::redirect(Request::makeUriAbsolute());
        }

        $this->_setViewData('title', 'Prihlásenie');
        $this->_setViewData('formAction', Request::makeUriAbsolute('login'));
    }
}