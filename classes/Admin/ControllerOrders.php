<?php

class Admin_ControllerOrders extends Admin_ControllerAbstract
{

    public function run() {

        // vyberieme vsetky otvorene objednavky
        $orders = Admin_ModelOrder::getOpenOrders();

        // preprocessing dat
        $neparny = true;
        foreach ($orders as $key => $order) {
            $order['row_class'] = (($neparny) ? 'odd' : 'even');
            $neparny = !$neparny;
            $order['status_class'] = Admin_ModelOrder::statusToClass($order['status']);
            $orders[$key] = $order;
        }

        $this->View->title = 'QuickPanel Admin';
        $this->View->title2 = 'otvorené objednávky, na ktorých sa pracuje';
        $this->View->records = $orders;
        $this->View->records_num = count($orders);
        $this->View->urlLogout = Request::makeUriAbsolute('logout');
    }
}