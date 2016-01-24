<?php

class Admin_ViewLogin extends Admin_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('loginHeader');

        $this->_renderSnippet('loginBody');

        $this->_renderSnippet('loginFooter');
    }
}