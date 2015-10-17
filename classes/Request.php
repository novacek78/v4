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
     * @var string Hovori, ci je to 'get', 'post', 'ajax'
     */
    private static $_requestType = '';

    /**
     * Povie, ci je aktualny request typu GET
     *
     * @return bool
     */
    public static function isGet() {

        if (empty(self::$_requestType))
            self::_initRequestType();

        return (self::$_requestType == 'GET');
    }

    private static function _initRequestType() {

        self::$_requestType = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Vrati pole POST dat, ktore prisli
     *
     * @return array
     */
    public static function getPostData() {

        return $_POST;
    }

    /**
     * Povie, ci je aktualny request typu POST
     *
     * @return bool
     */
    public static function isPost() {

        if (empty(self::$_requestType))
            self::_initRequestType();

        return (self::$_requestType == 'POST');
    }

    public static function makeUriRelative() {

        $arrArgs = func_get_args();
        return BASE_HREF . '' . implode('/', $arrArgs);
    }

    public static function makeUriAbsolute() {

        $arrArgs = func_get_args();
        return ((empty($_SERVER['HTTPS'])) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . BASE_HREF . '' . implode('/', $arrArgs);
    }

    public static function redirect($uri) {

        $currentUri = BASE_HREF . '' . self::getParam('uri', REQUEST_PARAM_STRING);

        if ($currentUri != $uri) { // aby nedoslo k zacyklenemu presmerovaniu napr. z 'login' na 'login'
            Logger::info("Redirecting to: $uri....");
            header('Location: ' . $uri);
            exit;
        }
    }

    /**
     * Vyhlada parameter daneho mena a vrati ho
     *
     * @param string $paramName
     * @param int    $type Datovy typ string|int|float (podla konstant REQUEST_PARAM_XXX)
     * @param bool   $isRequired
     * @param string $regexp
     * @param mixed  $default
     * @param bool   $fromPost Ci to je POST parameter
     * @return mixed
     */
    public static function getParam($paramName, $type, $isRequired = false, $regexp = '', $default = null, $fromPost = false) {  // ak je regexp posielany sem v dvojitych uvodzovkach, musia sa escape znaky este raz escapovat !

        $tmp = ($fromPost) ? self::_getPost($paramName) : self::_getGet($paramName);

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
                case REQUEST_PARAM_INT:
                    $tmp = (int)$tmp;
                    break;
                case REQUEST_PARAM_FLOAT:
                    $tmp = (float)$tmp;
                    break;
                case REQUEST_PARAM_STRING:
                    $tmp = (string)$tmp;
                    break;
                default:
                    Logger::error('Parameter type "' . $type . '" unknown.');
                    Logger::error("    URL request: $_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]");
                    exit("PARAMETER TYPE UNKNOWN");
                    break;
            }
        }

        if ( ! isset($tmp) && isset($default))
            $tmp = $default;

        return $tmp;
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
     * Vyhlada v GET/POST poli parameter daneho mena a nainicializuje ho do glob.pola $_PARAMS
     *
     * @param string $paramName
     * @param int    $type   Datovy typ string|int|float (podla konstant REQUEST_PARAM_XXX)
     * @param bool   $isRequired
     * @param string $regexp
     * @param mixed  $default
     * @param bool   $isPost Ci to je POST parameter
     */
    public static function initParam($paramName, $type, $isRequired = false, $regexp = '', $default = null, $isPost = false) {  // ak je regexp posielany sem v dvojitych uvodzovkach, musia sa escape znaky este raz escapovat !

        global $_PARAMS;

        $tmp = self::getParam($paramName, $type, $isRequired, $regexp, $default, $isPost);

        $_PARAMS[$paramName] = $tmp;
    }

    public static function getUrlParam($paramNumber) {

        global $_PARAMS;

        if (count($_PARAMS) > $paramNumber) {
            return $_PARAMS[$paramNumber];
        } else {
            Logger::error('Undefined param number: ' . $paramNumber . '. Request URI: ' . $_SERVER['REQUEST_URI']);
            return 'undefined_param';
        }
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