<?php

class Admin_ControllerHome extends Admin_ControllerAbstract
{

    public function run() {

        $pole = Db::fetchAll("SELECT * FROM qp1_users");

        $this->_setViewData('title', 'Administrácia systému QP');
        $this->_setViewData('urlLogout', Request::makeUriAbsolute('logout'));
        $this->_setViewData('data', count($pole));
    }
}