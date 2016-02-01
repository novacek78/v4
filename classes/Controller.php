<?php

abstract class Controller
{

    /**
     * @var View
     */
    protected $_View = null;

    /**
     * Controller nebude
     * @var bool
     */
    protected $_withoutView = false;


    public function __construct() {

        $this->_setView(null, true);
    }

    /**
     * Vytvori pohlad.
     *
     * @param string|null $viewName
     * @param bool $silent Ci ma zostat ticho, ak dany pohlad nenajde - inak hodi vynimku.
     * @throws Exception
     */
    protected function _setView($viewName = null, $silent = false) {

        if ($viewName === null) {
            // potrebujeme z nazvu ako 'Admin_ControllerLogin'  vyrobit 'Admin_ViewLogin'
            $viewName = str_replace('Controller', 'View', get_class($this));
        } else {
            $viewName = "Admin_View" . ucfirst($viewName);
        }

        if (class_exists($viewName))
            $this->_View = new $viewName();
        else {
            if ($silent)
                Logger::debug("Class $viewName not found!");
            else
                throw new Exception("Class $viewName not found!");
        }

    }

    public function render() {

        $this->_View->base_href = Request::makeUriAbsolute();

        $this->_View->render();
    }
}