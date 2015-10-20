<?php

class Quickplan_ViewLogin extends Quickplan_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('header');

        $this->_renderSnippet('loginForm');

        $this->_renderSnippet('footer');
    }
}