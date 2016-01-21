<?php

class Application
{

    /**
     * @var Controller
     */
    private $_Controller;


    public function run() {

        Logger::info('', false);

        $protocol = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
        Logger::debug( $_SERVER['REQUEST_METHOD'] . " " . $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

        Logger::info('App start...');

        session_start();

        // zistenie a spustenie daneho kontrolera
        $controllerName = CLASSES_PREFIX . "Controller" . Router::getControllerName();

        if (class_exists($controllerName)) {
            Logger::debug("Loading controller $controllerName...");
            $this->_Controller = new $controllerName();
            $this->_Controller->run();
            $this->_Controller->render();
        } else {
            throw new Exception('Unknown controller: ' . $controllerName);
        }
    }
}