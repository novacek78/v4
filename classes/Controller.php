<?php

abstract class Controller
{

    /**
     * @var View
     */
    protected $_View;

    /**
     * @var stdClass
     */
    protected $_viewData = null;

    /**
     * Inicializacia controllera
     */
    public function __construct() {

        $this->_viewData = new stdClass();
    }

    abstract public function run();

    public function render() {

        if ( ! isset($this->_View)) {
            // potrebujeme z nazvu ako 'Admin_ControllerLogin'  vyrobit 'Admin_ViewLogin'
            $viewName = str_replace('Controller', 'View', get_class($this));
            if (class_exists($viewName))
                $this->_View = new $viewName();
            else
                throw new Exception("Class $viewName not found!");
        }

        $this->_View->setData($this->_viewData);
        $this->_View->render();
    }

    /**
     * Ulozi data pre pouzitie v pohlade (View).
     * Ak je $value asociativne pole, bude vytvorene pole objektov stdClass.
     * Pracuje len s 1-rozmernymi poliami !
     * Ak je $value NULL, danu polozku z pola hodnot vymaze.
     *
     * @param string $name
     * @param mixed  $value Moze to byt : hodnota | non-assoc.pole | assoc.pole | null
     */
    protected function _setViewData($name, $value) {

        if ($value === null) {

            // vymazanie danej polozky z pola dat
            if (isset($this->_viewData->$name))
                unset($this->_viewData->$name);
        } else {

            if (is_array($value) && Utils::isAssociativeArray($value)) {

                if ( ! isset($this->_viewData->$name)) {
                    $this->_viewData->$name = new stdClass();
                }
                foreach ($value as $key => $val) {
                    if ($val === null)
                        unset($this->_viewData->{$name}->{$key});
                    else
                        $this->_viewData->{$name}->{$key} = $val;
                }
            } else {
                $this->_viewData->$name = $value;
            }
        }
    }
}