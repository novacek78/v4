<?php

class Admin_ControllerLogin extends Admin_ControllerAbstract
{

    public function run() {


        $this->_viewData = array(
            'title' => 'prihlasko',
            'pozdrav' => 'ahoj',
            'body' => 'Cum sem ahaa!'
        );

        $this->_render();
    }
}