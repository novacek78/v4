<?php

/**
 * Created by PhpStorm.
 * User: enovacek
 * Date: 11. 10. 2015
 * Time: 20:29
 */
class Utils {


    /**
     * Zisti, ci pole je asociativne
     *
     * @param array $arr
     * @return bool
     */
    public static function isAssociativeArray($arr) {

        $isAssoc = false;

        foreach ($arr as $key => $val) {
            if ( ! is_numeric($key)) {
                $isAssoc = true;
                break;
            }
        }

        return $isAssoc;
//        return array_keys($arr) !== range(0, count($arr) - 1);
    }

}