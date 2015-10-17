<?php

abstract class Projecto_ControllerAbstract extends Controller
{

    /**
     * Sem ide kod, ktory sa spusti pri kazdom oddedenom controlleri
     */
    public function __construct() {

        parent::__construct();

        $this->_loggedUserOnly();
    }

    /**
     * Zisti, ci je uzivatel prihlaseny a ak nie, redirectne ho na login page
     */
    protected function _loggedUserOnly() {

        // kontrola, ci je user prihlaseny
        if ( ! isset($_SESSION['isLoggedIn']) || ($_SESSION['isLoggedIn'] !== true)) {

            unset($_SESSION);
            Request::redirect(Request::makeUriRelative('login'));
        }
    }
}