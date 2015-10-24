<?php

/**
 * POZOR, tento controller nie je oddedeny z Abstract controllera, lebo tam sa vykonava kontrola prihlaseneho usera
 * a nepresiel by tade prihlasovaci POST request
 *
 * Class Admin_ControllerLogin
 */
class Admin_ControllerLogin extends Controller
{

    public function run() {

        if (isset($_SESSION['isLoggedIn']) && ($_SESSION['isLoggedIn'] === true)) {
            Request::redirect(Request::makeUriAbsolute());
        }

        if (Request::isPost()) {
            $PostData = Request::getPostData();

            if ($PostData->formName == 'login') {
                $User = new Admin_ModelUser();

                if ($User->login($PostData)) {
                    Request::redirect(Request::makeUriAbsolute());
                } else {
                    Request::redirect(Request::makeUriAbsolute('login'));
                }
            }
        }

        $this->_setViewData('title', 'Prihlásenie');
        $this->_setViewData('formAction', Request::makeUriAbsolute('login'));

    }
}