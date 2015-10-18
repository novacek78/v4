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

    /**
     * Prekonvertuje textu do pozadovaneho kodovania
     *
     * @param string $text
     * @param string $targetEncoding
     * @return string
     */
    public static function convertEncoding($text, $targetEncoding = 'utf-8') {

        $result = array();

        $arr = imap_mime_header_decode($text);

        foreach ($arr as $Part) {

            if (($Part->charset != 'default') && (strtolower($Part->charset) != $targetEncoding)) {
                $result[] = mb_convert_encoding($Part->text, $targetEncoding, $Part->charset);
            } else {
                $result[] = $Part->text;
            }
        }

        return implode(' ', $result);
    }
}