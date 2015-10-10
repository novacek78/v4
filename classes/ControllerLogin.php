<?php

class ControllerLogin extends Controller
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