<?php

class Logger
{


    public static function debug($data, $withTimestamp = true) {

        if (ENV == 'development')
            self::_log($data, 'dbg', $withTimestamp);
    }

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

        if (is_bool($data))
            $data = (($data) ? 'boolean true' : 'boolean false');

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
}