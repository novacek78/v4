<?php

class Admin_ControllerHome extends Admin_ControllerAbstract
{

    public function run() {

        $this->_setViewData('title', 'Administrácia systému QP');
    }
}