<?php

abstract class Controller
{

    /**
     * @var View
     */
    protected $View;


    public function __construct() {

        // potrebujeme z nazvu ako 'Admin_ControllerLogin'  vyrobit 'Admin_ViewLogin'
        $viewName = str_replace('Controller', 'View', get_class($this));
        if (class_exists($viewName))
            $this->View = new $viewName();
        else
            throw new Exception("Class $viewName not found!");
    }

    abstract public function run();

    public function render() {

        $this->View->base_href = Request::makeUriAbsolute();

        $this->View->render();
    }
}