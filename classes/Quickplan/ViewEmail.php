<?php

class Quickplan_ViewEmail extends Quickplan_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('header');

        $this->_renderSnippet('pageEmail');

        $this->_renderSnippet('footer');
    }
}