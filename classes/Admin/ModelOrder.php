<?php

class Admin_ModelOrder
{

    /**
     * Podla ciselneho kodu stavu objednavky vrati nazov triedy,
     * ktora sa ma pouzit v DataTables na farebne oznacenie stavu.
     *
     * @param $code int
     * @return string
     */
    static public function statusToClass($code) {

        switch ($code) {
            // modra
            case 420:
            case 510:
            case 512:
            case 600:
            case 610:
                $result = 'label-info';
                break;
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