<?php

class Application
{

    /**
     * @var Controller
     */
    private $_Controller;


    public function run() {

        Logger::info('', false);
        Logger::info('App start...');

        session_start();

        Db::init(DB_NAME, DB_USER, DB_PWD, DB_HOST, DB_ENGINE);

        $controllerName = Router::getControllerName();

        // spustenie daneho kontrollera
        $controllerName = CLASSES_PREFIX . "Controller" . $controllerName;
        if (class_exists($controllerName)) {

            $this->_Controller = new $controllerName();
            $this->_Controller->run();
            $this->_Controller->render();
        } else {
            throw new Exception('Unknown controller: ' . $controllerName);
        }
    }
}