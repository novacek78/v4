<?php

// konfiguracia
require 'config_common.php';


// autoloader tried
spl_autoload_register(function ($class) {

    $classFileName = CLASS_DIR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($classFileName))
        include $classFileName;
    else
        Logger::error('Class autoloading: File not exist: ' . $classFileName);
});