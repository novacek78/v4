<?php

define('APP_DIR', 'Admin/');
define('CLASSES_PREFIX', 'Admin_');
define('BASE_HREF', '/qpapps/admin/?uri=');

define('DB_HOST', 'localhost');
define('DB_ENGINE', 'mysql');
define('DB_NAME', 'quickpanel');
define('DB_USER', 'root');
define('DB_PWD', '');


// ------- routes -----------------------------------------------

$routes = array();

$ctrl = 'Orders';
$routes[$ctrl][] = 'orders';
$routes[$ctrl][] = 'orders/test';
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

