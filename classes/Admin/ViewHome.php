<?php

class Admin_ViewHome extends Admin_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('header');

        $this->_renderSnippet('pageHome');

        $this->_renderSnippet('footer');
    }
}