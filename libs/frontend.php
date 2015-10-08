<?php


function TransT($id) {

    global $_TXT;

    if (( ! isset($_TXT[$id])) || empty($_TXT[$id])) {
        // logovanie chybajuceho prekladu
        Logger::error("Chybajuci preklad c.$id pre jazyk '{$_SESSION['language']}'");
        return "- $id -";
    } else
        return $_TXT[$id];
}
