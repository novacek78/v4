<?php

class Application
{

    /**
     * @var Controller
     */
    private $_Controller;


    public function run() {

        session_start();

        $controllerName = Router::getControllerName();

        // spustenie daneho kontrollera
        $controllerName = CLASSES_PREFIX . "Controller" . $controllerName;
        if (class_exists($controllerName)) {

            $this->_Controller = new $controllerName();
            $this->_Controller->run();
        } else {
            throw new Exception('Unknown controller: ' . $controllerName);
        }
    }
}