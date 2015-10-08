<?php

class Logger
{


    private static function _log($data, $level, $withTimestamp) {

        $prependText = ($withTimestamp) ? date('j.n.Y H:i:s') . ' : ' : '';

        if ($level == 'err') {
            $fileNameSuffix = '-error';

            // naformatovanie dat tak aby boli pekne zarovnane
            $sid = str_pad(session_id(), 26, ' ');
            $ip = str_pad($_SERVER['REMOTE_ADDR'], 15, ' ');

            $prependText .= "SID:$sid , IP:$ip , ";
        } else {
            $fileNameSuffix = '';
        }

        if (is_array($data) || is_object($data)) {
            $data = PHP_EOL . var_export($data, true);
        }

        try {
            $f = FOpen(LOG_DIR . date('Y-m-d') . "$fileNameSuffix.txt", "a");
            FWrite($f, $prependText . $data . PHP_EOL);
            FClose($f);
        } catch (Exception $e) {
        }
//        LogTxt(' [ERROR] ' . $what);
    }

    public static function info($data, $withTimestamp = true) {

        self::_log($data, 'inf', $withTimestamp);
    }

    public static function error($data, $withTimestamp = true) {

        self::_log($data, 'err', $withTimestamp);
    }

    function LogErr($what, $timestamp = true) {

        $f = FOpen(realpath($_SERVER["DOCUMENT_ROOT"]) . "/logs/" . date('Y-m-d') . "-error.txt", "a");
        if ($timestamp)
            FWrite($f, date('j.n.Y H:i') . " , IP:" . $_SERVER['REMOTE_ADDR'] . " , $what\r\n");
        else
            FWrite($f, "IP:" . $_SERVER['REMOTE_ADDR'] . " , $what\r\n");
        FClose($f);
        LogTxt(' [ERROR] ' . $what);
    }

    function LogTxt($what, $timestamp = true) {

        if ($what == '') $timestamp = false; // ak sa loguje prazdny riadok, je to len na oddelenie, nedavat tam timestamp
        if (1) {  // vyp/zap logovania
            $f = FOpen(realpath($_SERVER["DOCUMENT_ROOT"]) . "/logs/" . date('Y-m-d') . "-log.txt", "a");
            if ($timestamp)
                FWrite($f, date('j.n.Y H:i') . " $what\r\n");
            else
                FWrite($f, "$what\r\n");
            FClose($f);
        }
    }
}