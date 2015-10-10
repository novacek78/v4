<?php

class Controller
{

    /**
     * @var View
     */
    protected $_View;

    /**
     * @var array
     */
    protected $_viewData;


    public function run() {
    }

    protected function _render() {

        if ( ! isset($this->_View)) {
            $viewName = 'View' . str_replace('Controller', '', get_class($this));
            $this->_View = new $viewName();
        }

        $this->_View->setData($this->_viewData);
        $this->_View->render();
    }
}