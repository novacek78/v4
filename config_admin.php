<?php

define('APP_DIR', 'Admin/');
define('CLASSES_PREFIX', 'Admin_');
define('BASE_HREF', '/qpapps/admin/?uri=');

switch (ENV){
    case 'production':
        define('DB_HOST', 'localhost');
        define('DB_ENGINE', 'mysql');
        define('DB_NAME', 'qp_test');
        define('DB_USER', 'qp_test');
        define('DB_PWD', 'matahari');
        break;

    case 'testing':
        define('DB_HOST', 'localhost');
        define('DB_ENGINE', 'mysql');
        define('DB_NAME', 'qp_test');
        define('DB_USER', 'qp_test');
        define('DB_PWD', 'matahari');
        break;

    case 'development':
        define('DB_HOST', 'localhost');
        define('DB_ENGINE', 'mysql');
        define('DB_NAME', 'qp_local');
        define('DB_USER', 'root');
        define('DB_PWD', '');
        break;
}


// ------- routes -----------------------------------------------

$routes = array();

$ctrl = 'Orders';
$routes[$ctrl][] = 'orders/open';
$routes[$ctrl][] = 'orders/closed';
$routes[$ctrl][] = 'orders/all';
$routes[$ctrl][] = 'orders/view/*';
$routes[$ctrl][] = 'orders/edit/*';
$routes[$ctrl][] = 'orders/delete/*';

$ctrl = 'Login';
$routes[$ctrl][] = 'login';

$ctrl = 'Logout';
$routes[$ctrl][] = 'logout';

$ctrl = 'Utils';
$routes[$ctrl][] = 'tmppanels';

$ctrl = 'Home';
$routes[$ctrl][] = '';
$routes[$ctrl][] = '*';
$routes[$ctrl][] = '*/*';
$routes[$ctrl][] = '*/*/*';
$routes[$ctrl][] = '*/*/*/*';

define('ROUTES', serialize($routes));

