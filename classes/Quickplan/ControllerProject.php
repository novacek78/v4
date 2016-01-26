<?php

class Quickplan_ControllerProject extends Quickplan_ControllerAbstract
{

    public function run() {

        $this->View->title = 'QuickPlan - projects';
        $this->View->projects = '
        Project 01<br>
        Project 02<br>
        Project 03<br>
        ';
        $this->View->history = '
        email 3<br>
        telefonat 2<br>
        email 1<br>
        ';
        $this->View->detail = '
        Dobry den.<br>
        Urobime to takto: ....<br>
        ';
    }
}