<?php

class Admin_ModelOrder
{

    /**
     * Vytiahne z DB vsetky otvorene objednavky
     *
     * @param int $limit
     * @return array|false
     */
    static public function getOpenOrders($limit = null) {

        $sql = 'SELECT order_id, user_id, order_num, company, CONCAT(name, \' \', lastname) as full_name, CONCAT(street, \', \', zip, \' \', city, \', \', country) as address, email,
ROUND(price_nodph/kurz_k_eur) as price, `status`, qp1_orderstatus.title_sk as status_txt, ROUND(DATEDIFF(NOW(), confirmed)/7, 1) as vek
FROM qp1_orders
LEFT JOIN qp1_orderstatus ON qp1_orderstatus.status_id = qp1_orders.status
WHERE (status < 900 AND status >= 420 AND status <> 444)
ORDER BY order_id DESC ';

        if (isset($limit))
            $sql .= "LIMIT $limit";

        return Db::fetchAll($sql);
    }

    /**
     * Podla ciselneho kodu stavu objednavky vrati nazov triedy,
     * ktora sa ma pouzit v DataTables na farebne oznacenie stavu.
     *
     * @param int $code
     * @return string
     */
    static public function statusToClass($code) {

        switch ($code) {
//            // modra
//            case 420:
//            case 510:
//            case 512:
//            case 600:
//            case 610:
//                $result = 'label-info';
//                break;
            // zelena
            case 500:
            case 501:
            case 700:
            case 800:
                $result = 'label-success';
                break;
            // oranzova
            case 707:
                $result = 'label-warning';
                break;
            // cervena
            case 440:
            case 444:
                $result = 'label-danger';
                break;
            // seda
            default:
                $result = 'label-default';
        }

        return $result;
    }
}