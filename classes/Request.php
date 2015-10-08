<?php

/**
 * Created by PhpStorm.
 * User: enovacek
 * Date: 8. 10. 2015
 * Time: 11:10
 */
class Request
{

    /**
     * @param $name
     * @return null|string
     */
    private static function _getGet($name) {

        if (isset($_GET[$name]))
            return $_GET[$name];
        else
            return null;
    }

    /**
     * @param $name
     * @return null|string
     */
    private static function _getPost($name) {

        if (isset($_POST[$name]))
            return $_POST[$name];
        else
            return null;
    }

    /**
     * Vyhlada v GET/POST poli parameter daneho mena a nainicializuje ho do glob.pola $_PARAMS
     *
     * @param string $paramName
     * @param string $type   Datovy typ string|int|float
     * @param bool   $isRequired
     * @param string $regexp
     * @param mixed  $default
     * @param bool   $isPost Ci to je POST parameter
     */
    public static function getParam($paramName, $type, $isRequired = false, $regexp = '', $default = null, $isPost = false) {  // ak je regexp posielany sem v dvojitych uvodzovkach, musia sa escape znaky este raz escapovat !

        global $_PARAMS;

        $tmp = ($isPost) ? self::_getPost($paramName) : self::_getGet($paramName);

        if (isset($tmp) && ($regexp != '')) {
            if (preg_match($regexp, $tmp) !== 1) {
                Logger::error('Parameter "' . $paramName . '" doesn\'t match regexp "' . $regexp . '"');
                Logger::error("  URL requested: $_SERVER[SERVER_NAME] $_SERVER[REQUEST_URI]");
                $tmp = 'regex-mismatch';
            }
        }

        if ($isRequired) {
            if ( ! isset($tmp) || ($tmp == '')) {
                Logger::error('Missing parameter "' . $paramName . '" in ' . $_SERVER["SCRIPT_NAME"]);
                Logger::error("  URL requested: $_SERVER[SERVER_NAME] $_SERVER[REQUEST_URI]");
                exit("PARAMETER MISSING");
            }
        }

        if (isset($tmp)) {
            switch ($type) {
                case 'int':
                    $tmp = (int)$tmp;
                    break;
                case 'float':
                    $tmp = (float)$tmp;
                    break;
                case 'string':
                    $tmp = (string)$tmp;
                    break;
                default:
                    Logger::error('Parameter type"' . $type . '" unknown.');
                    Logger::error("  URL requested: $_SERVER[SERVER_NAME] $_SERVER[REQUEST_URI]");
                    exit("PARAMETER TYPE UNKNOWN");
                    break;
            }
        }

        if ( ! isset($tmp) && isset($default))
            $tmp = $default;

        $_PARAMS[$paramName] = $tmp;
    }

    /**
     * Zisti, aky jazyk uzivatel preferuje
     *
     * @param string $http_accept_language
     * @return string
     */
    public static function getPreferredLanguage($http_accept_language = "auto") {

        // ktore jazyky su dostupne (ak sa pozadovany jazyk tu nenachadza, zoberie sa prvy v zozname)
        $available_languages = array('en', 'sk', 'cs');

        // if $http_accept_language was left out, read it from the HTTP-Header
        if ($http_accept_language == "auto") $http_accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';

        // standard  for HTTP_ACCEPT_LANGUAGE is defined under
        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
        // pattern to find is therefore something like this:
        //    1#( language-range [ ";" "q" "=" qvalue ] )
        // where:
        //    language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
        //    qvalue         = ( "0" [ "." 0*3DIGIT ] )
        //            | ( "1" [ "." 0*3("0") ] )
        preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" .
                       "(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
                       $http_accept_language, $hits, PREG_SET_ORDER);

        // default language (in case of no hits) is the first in the array
        $bestlang = $available_languages[0];
        $bestqval = 0;

        foreach ($hits as $arr) {
            // read data from the array of this hit
            $langprefix = strtolower($arr[1]);
            if ( ! empty($arr[3])) {
                $langrange = strtolower($arr[3]);
                $language = $langprefix . "-" . $langrange;
            } else $language = $langprefix;
            $qvalue = 1.0;
            if ( ! empty($arr[5])) $qvalue = floatval($arr[5]);

            // find q-maximal language
            if (in_array($language, $available_languages) && ($qvalue > $bestqval)) {
                $bestlang = $language;
                $bestqval = $qvalue;
            } // if no direct hit, try the prefix only but decrease q-value by 10% (as http_negotiate_language does)
            else if (in_array($langprefix, $available_languages) && (($qvalue * 0.9) > $bestqval)) {
                $bestlang = $langprefix;
                $bestqval = $qvalue * 0.9;
            }
        }
        return $bestlang;
    }
}