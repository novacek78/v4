<?php

/**
 * POZOR, tento controller nie je oddedeny z Abstract controllera, lebo tam sa vykonava kontrola prihlaseneho usera
 * a nepresiel by tade prihlasovaci POST request
 *
 * Class Admin_ControllerLogin
 */
class Quickplan_ControllerLogin extends Controller
{

    public function run() {

        if (Request::isPost()) {
            $PostData = Request::getPostData();

            if ($PostData->formName == 'login') {
                $User = new Quickplan_ModelUser();

                if ($User->login($PostData)) {
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