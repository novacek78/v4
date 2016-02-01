<?php

class Admin_ModelOrder
{

    /**
     * Vytiahne z DB vsetky objednavky, na ktorych my musime robit
     *
     * @return array|false
     */
    static public function getActiveOrders() {

        return self::getOrders('status < 900 AND (status IN (500, 501, 502, 700, 707, 800))');
    }

    /**
     * Vytiahne z DB vsetky objednavky
     *
     * @param string $where
     * @param string $orderBy
     * @param number $limit
     * @return array|false
     */
    static public function getOrders($where = null, $orderBy = null, $limit = null) {

        $sql = 'SELECT order_id, user_id, order_num, company, CONCAT(name, \' \', lastname) as full_name, CONCAT(street, \', \', zip, \' \', city, \', \', country) as address, email,
ROUND(price_nodph/kurz_k_eur) as price, `status`, qp1_orderstatus.title_sk as status_txt, ROUND(DATEDIFF(NOW(), confirmed)/7, 1) as vek
FROM qp1_orders
LEFT JOIN qp1_orderstatus ON qp1_orderstatus.status_id = qp1_orders.status ';

        $where = (empty($where) ? '' : "WHERE $where ");
        $orderBy = (empty($orderBy) ? 'ORDER BY order_num DESC' : "ORDER BY $orderBy ");
        $limit = (empty($limit) ? '' : "LIMIT $limit ");

        $sql .= $where . $orderBy . $limit;

        return Db::fetchAll($sql);
    }

    /**
     * Vytiahne z DB vsetky otvorene objednavky
     *
     * @return array|false
     */
    static public function getOpenOrders() {

        return self::getOrders('(status < 900 AND status >= 420 AND status <> 444)');
    }

    /**
     * Vytiahne z DB vsetky uzatvorene objednavky
     *
     * @return array|false
     */
    static public function getClosedOrders() {

        return self::getOrders('status = 900');
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