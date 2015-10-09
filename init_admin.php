<?php

require 'init_common.php';

// konfiguracia
require 'config_admin.php';

// ine kniznice


// inicializacny kod
Request::getParam('uri', 'string');

// rozbije URL na jednotlive parametre
$_PARAMS = explode('/', $_PARAMS['uri']);

// vlozi do pola parametrov z URL prazdny prvok na poziciu '0'
// aby prvy parameter z URL bol na pozicii 1, druhy na pozicii 2, atd...
$insert = '';
array_splice($_PARAMS, 0, 0, $insert);