<?php

class Admin_ControllerHome extends Admin_ControllerAbstract
{

    public function run() {

        Logger::info('I am at home!');
        $this->_setViewData('title', 'Administrácia systému QP');
    }
}