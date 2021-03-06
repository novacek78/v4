<?php

/**
 * Class Quickplan_ControllerLogout
 */
class Quickplan_ControllerLogout extends Quickplan_ControllerAbstract
{

    public function run() {

        $_SESSION['isLoggedIn'] = '';
        Logger::debug('User logged out.');
        Request::redirect(Request::makeUriRelative('login'));
    }
}