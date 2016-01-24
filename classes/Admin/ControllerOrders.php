<?php

class Admin_ControllerOrders extends Admin_ControllerAbstract
{

    public function run() {

        $this->_setViewData('title', 'QuickPanel Admin');
        $this->_setViewData('title2', 'otvorené objednávky, na ktorých sa pracuje');
        $this->_setViewData('urlLogout', Request::makeUriAbsolute('logout'));
    }
}