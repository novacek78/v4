<?php

class Admin_ViewLogin extends Admin_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('header');

        $this->_renderSnippet('loginForm');

        $this->_renderSnippet('footer');
    }
}