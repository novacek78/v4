<?php

abstract class Admin_ControllerAbstract extends Controller
{

    /**
     * Sem ide kod, ktory sa spusti pri kazdom oddedenom controlleri
     */
    public function __construct() {


        $this->_loggedUserOnly();
    }

    /**
     * Zisti, ci je uzivatel prihlaseny a ak nie, redirectne ho na login page
     */
    protected function _loggedUserOnly() {

        // kontrola, ci je user prihlaseny
        if ( ! isset($_SESSION['isLoggeedIn']) || ($_SESSION['isLoggeedIn'] !== true)) {

            // redirect na login (ak uz nie sme na login page)
            unset($_SESSION);
            if (Request::getParam('uri', REQUEST_PARAM_STRING) != 'login')
                Request::redirect(Request::makeUri('login'));
        }
    }
}