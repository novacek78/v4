<?php

// konfiguracia
require 'config_common.php';



// autoloader tried
spl_autoload_register(function ($class) {
    include CLASS_DIR . $class . '.php';
});