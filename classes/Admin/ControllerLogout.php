<?php

/**
 * Class Admin_ControllerLogout
 */
class Admin_ControllerLogout extends Admin_ControllerAbstract
{

    public function run() {

        $_SESSION['isLoggedIn'] = '';
        Logger::debug('User logged out.');
        Request::redirect(Request::makeUriRelative('login'));
    }
}