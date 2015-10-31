<?php

/**
 * Class Admin_ControllerLogin
 */
class Quickplan_ControllerLogout extends Quickplan_ControllerAbstract
{

    public function run() {

        $_SESSION['isLoggedIn'] = '';
        Request::redirect(Request::makeUriRelative('login'));
    }
}