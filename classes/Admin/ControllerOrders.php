<?php

class Admin_ControllerOrders extends Admin_ControllerAbstract
{

    public function run() {

        // vyberieme vsetky otvorene objednavky
        $orders = Db::fetchAll('
SELECT order_id, user_id, order_num, company, CONCAT(name, \' \', lastname) as full_name, CONCAT(street, \', \', zip, \' \', city, \', \', country) as address, email, ROUND(price_nodph/kurz_k_eur) as price, `status`, qp1_orderstatus.title_sk as status_txt
FROM qp1_orders
LEFT JOIN qp1_orderstatus ON qp1_orderstatus.status_id = qp1_orders.status
WHERE (status < 900 AND status >= 420 AND status <> 444)
ORDER BY order_id DESC;
');

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