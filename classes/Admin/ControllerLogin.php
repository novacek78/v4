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

        if (Request::isPost()) {
            $PostData = Request::getPostData();

            if ($PostData->formName == 'login') {
                $User = new Admin_ModelUser();

                if ($User->login($PostData)) {
                    Logger::debug('User logged in.');
                    Request::redirect(Request::makeUriAbsolute('orders', 'open')); // default home page
                } else {
                    Logger::debug("User login failed ({$PostData->formName})");
                    Request::redirect(Request::makeUriAbsolute('login'));
                }
            }
        }

        // ak niekto pride na login a uz je prihlaseny, redirect na homepage
        if (isset($_SESSION['isLoggedIn']) && ($_SESSION['isLoggedIn'] === true)) {
            Request::redirect(Request::makeUriAbsolute());
        }

        $this->_setViewData('title', 'Prihlásenie');
        $this->_setViewData('formAction', Request::makeUriAbsolute('login'));
    }
}