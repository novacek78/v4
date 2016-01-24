<?php

class Admin_ViewOrders extends Admin_ViewAbstract
{

    public function render() {

        $this->_renderSnippet('ordersHeader');

        $this->_renderSnippet('orders');

        $this->_renderSnippet('ordersFooter');
    }
}