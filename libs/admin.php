<?php


function LogAdmActivity($usrName, $activity, $oldVal = '', $newVal = '', $note = '') {

    $sqlStatement = "INSERT INTO adm_activity (user_login, aktivita, stara_hodnota, nova_hodnota, poznamka) VALUES ('$usrName', '$activity', '$oldVal', '$newVal', '$note')";
    if ( ! sql($sqlStatement))
        Logger::error("Nepodarilo sa zapisat do admin-logu v DB. SQL:\n$sqlStatement");
}

function UserHasAccess($user, $action, $what) {

    $v = sql("SELECT rights_$action AS rights FROM adm_users WHERE user_login='$user'");
    $z = sqlGetRecord($v);
    if ((strpos($z['rights'], $what) !== false) || ($z['rights'] == 'all'))
        return true;
    else
        return false;
}

function SkontrolujEmail($email) {

    // kontrola, ci obsahuje zavinac
    if ( ! ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        // nespravny email - zly pocet znakov v jednej sekcii alebo zly pocet zavinacov
        return false;
    }
    // rozhodime to podla @ a podla .
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if ( ! ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }
    if ( ! ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        // kontrola, ci domena je IP, ak nie, malo by to byt platne domenove meno
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // nie je dost casti na domenu (min. 2)
        }

        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if ( ! ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}

function Date2Eu($datum) {

    $datum = substr($datum, 0, 10);
    $pole = explode('-', $datum);
    return $pole[2] . '.' . $pole[1] . '.' . $pole[0];
}

function Date2Us($datum) {

    $pole = explode('.', $datum);
    return $pole[2] . '-' . $pole[1] . '-' . $pole[0];
}

function w($txt, $withNL = true) {

    global $_OutFile;

    if ($withNL) fwrite($_OutFile, $txt . "\r\n");
    else fwrite($_OutFile, $txt);
}

function DelDiakritika($text) {

    /*
      $prevodna_tabulka = Array(
        'ä'=>'a',
        'Ä'=>'A',
        'á'=>'a',
        'Á'=>'A',
        'a'=>'a',
        'A'=>'A',
        'a'=>'a',
        'A'=>'A',
        'â'=>'a',
        'Â'=>'A',
        'č'=>'c',
        'Č'=>'C',
        'ć'=>'c',
        'Ć'=>'C',
        'ď'=>'d',
        'Ď'=>'D',
        'ě'=>'e',
        'Ě'=>'E',
        'é'=>'e',
        'É'=>'E',
        'ë'=>'e',
        'Ë'=>'E',
        'e'=>'e',
        'E'=>'E',
        'e'=>'e',
        'E'=>'E',
        'í'=>'i',
        'Í'=>'I',
        'i'=>'i',
        'I'=>'I',
        'i'=>'i',
        'I'=>'I',
        'î'=>'i',
        'Î'=>'I',
        'ľ'=>'l',
        'Ľ'=>'L',
        'ĺ'=>'l',
        'Ĺ'=>'L',
        'ń'=>'n',
        'Ń'=>'N',
        'ň'=>'n',
        'Ň'=>'N',
        'n'=>'n',
        'N'=>'N',
        'ó'=>'o',
        'Ó'=>'O',
        'ö'=>'o',
        'Ö'=>'O',
        'ô'=>'o',
        'Ô'=>'O',
        'o'=>'o',
        'O'=>'O',
        'o'=>'o',
        'O'=>'O',
        'ő'=>'o',
        'Ő'=>'O',
        'ř'=>'r',
        'Ř'=>'R',
        'ŕ'=>'r',
        'Ŕ'=>'R',
        'š'=>'s',
        'Š'=>'S',
        'ś'=>'s',
        'Ś'=>'S',
        'ť'=>'t',
        'Ť'=>'T',
        'ú'=>'u',
        'Ú'=>'U',
        'ů'=>'u',
        'Ů'=>'U',
        'ü'=>'u',
        'Ü'=>'U',
        'u'=>'u',
        'U'=>'U',
        'u'=>'u',
        'U'=>'U',
        'u'=>'u',
        'U'=>'U',
        'ý'=>'y',
        'Ý'=>'Y',
        'ž'=>'z',
        'Ž'=>'Z',
        'ź'=>'z',
        'Ź'=>'Z'
      );
      return $text."---".strtr($text, $prevodna_tabulka);
    */

    $from = array('á', 'ä', 'č', 'ď', 'é', 'ě', 'í', 'ľ', 'ĺ', 'ň', 'ó', 'ô', 'ŕ', 'ř', 'š', 'ť', 'ú', 'ů', 'ý', 'ž', 'Á', 'Ä', 'Č', 'Ď', 'É', 'Ě', 'Í', 'Ľ', 'Ĺ', 'Ň', 'Ó', 'Ô', 'Ŕ', 'Ř', 'Š', 'Ť', 'Ú', 'Ů', 'Ý', 'Ž');
    $to = array('a', 'a', 'c', 'd', 'e', 'e', 'i', 'l', 'l', 'n', 'o', 'o', 'r', 'r', 's', 't', 'u', 'u', 'y', 'z', 'A', 'A', 'C', 'D', 'E', 'E', 'I', 'L', 'L', 'N', 'O', 'O', 'R', 'R', 'S', 'T', 'U', 'U', 'Y', 'Z');
    return str_replace($from, $to, $text);
}

/**
 * Odstrani z pola polozky s numerickym klucom
 *
 * @param $arr Array
 *
 * @return Array
 */
function removeNumKeys($arr) {

    foreach ($arr as $key => $val) {
        if (is_int($key)) {
            unset($arr[$key]);
        }
    }

    return $arr;
}

