<?php

/**
 * Class Admin_ControllerLogout
 */
class Admin_ControllerLogout extends Admin_ControllerAbstract
{

    public function __construct() {

        // prebity rodicovsky construct aby nevytvaral View
    }
    public function run() {

        $_SESSION['isLoggedIn'] = '';
        Logger::debug('User logged out.');
        Request::redirect(Request::makeUriRelative('login'));
    }
}