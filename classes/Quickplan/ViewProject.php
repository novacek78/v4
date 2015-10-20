<?php

class Quickplan_ViewProject extends Quickplan_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('header');

        $this->_renderSnippet('pageProject');

        $this->_renderSnippet('footer');
    }
}