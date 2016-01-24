<?php

class Admin_ViewLogin extends Admin_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('loginHeader');

        $this->_renderSnippet('login');

        $this->_renderSnippet('loginFooter');
    }
}