<?php

class Projecto_ViewProject extends Projecto_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('header');

        $this->_renderSnippet('pageProject');

        $this->_renderSnippet('footer');
    }
}