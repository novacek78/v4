<?php

class Admin_ControllerOrders extends Admin_ControllerAbstract
{

    public function run() {

        switch (Request::getParamByName('uri', REQUEST_PARAM_STRING)) {
            case 'orders/active':
                // vyberieme vsetky objednavky na ktorych treba robit
                $this->_View->title = 'Aktívne objednávky';
                $this->_View->title2 = 'otvorené objednávky, na ktorých sa práve pracuje';
                $orders = Admin_ModelOrder::getActiveOrders();
                break;
            case 'orders/open':
                // vyberieme vsetky otvorene objednavky
                $this->_View->title = 'Otvorené objednávky';
                $this->_View->title2 = 'rozpracované';
                $orders = Admin_ModelOrder::getOpenOrders();
                break;
            case 'orders/closed':
                // vyberieme vsetky uzatvorene objednavky
                $this->_View->title = 'Uzatvorené objednávky';
                $this->_View->title2 = 'uzatvorené/odoslané objednávky';
                $orders = Admin_ModelOrder::getClosedOrders();
                break;
            default:
                $this->_View->title = 'Všetky objednávky';
                $this->_View->title2 = 'celá história';
                $orders = Admin_ModelOrder::getOrders();
        }


        // preprocessing dat
        $neparny = true;
        foreach ($orders as $key => $order) {
            $order['row_class'] = (($neparny) ? 'odd' : 'even');
            $neparny = !$neparny;
            $order['status_class'] = Admin_ModelOrder::statusToClass($order['status']);
            $orders[$key] = $order;
        }

        $this->_View->records = $orders;
        $this->_View->records_num = count($orders);
        $this->_View->urlLogout = Request::makeUriRelative('logout');
    }
}