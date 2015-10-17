<?php

class Projecto_ControllerHome extends Projecto_ControllerAbstract
{

    public function run() {

        // podla preferencii usera to tu presmeruje
        if (1) {
            Request::redirect(Request::makeUriRelative('email'));
        } else {
            Request::redirect(Request::makeUriRelative('project'));
        }
    }
}