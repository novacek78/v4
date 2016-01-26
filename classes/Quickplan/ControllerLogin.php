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
                    Logger::debug('User logged in.');
                    Request::redirect(Request::makeUriAbsolute()); // default home page
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

        $this->View->title = 'Prihlásenie';
        $this->View->formAction = Request::makeUriAbsolute('login');
    }
}