<?php

class Projecto_ControllerProject extends Projecto_ControllerAbstract
{

    public function run() {

        $this->_setViewData('title', 'Projecto:projects');
        $this->_setViewData('projects', '
        Project 01<br>
        Project 02<br>
        Project 03<br>
        ');
        $this->_setViewData('history', '
        email 3<br>
        telefonat 2<br>
        email 1<br>
        ');
        $this->_setViewData('detail', '
        Dobry den.<br>
        Urobime to takto: ....<br>
        ');
    }
}