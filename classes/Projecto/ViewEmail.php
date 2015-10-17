<?php

class Projecto_ViewEmail extends Projecto_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('header');

        $this->_renderSnippet('pageEmail');

        $this->_renderSnippet('footer');
    }
}