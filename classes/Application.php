<?php

class Application
{

    /**
     * @var Controller
     */
    private $_Controller;


    public function run() {

        session_start();

        // kontrola, ci je user prihlaseny
        if (isset($_SESSION['isLoggeedIn']) && ($_SESSION['isLoggeedIn'] === true)) {
            $controllerName = Router::run();
        } else {
            $controllerName = 'Login';
        }

        // spustenie daneho kontrollera
        $controllerName = "Controller$controllerName";
        if (class_exists($controllerName)) {

            $this->_Controller = new $controllerName();
            $this->_Controller->run();
        } else {
            throw new Exception('Unknown controller: ' . $controllerName);
        }
    }
}