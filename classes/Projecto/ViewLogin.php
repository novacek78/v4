<?php

class Projecto_ViewLogin extends Projecto_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('header');

        $this->_renderSnippet('loginForm');

        $this->_renderSnippet('footer');
    }
}