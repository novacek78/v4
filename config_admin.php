<?php

define('APP_DIR', 'Admin/');
define('CLASSES_PREFIX', 'Admin_');
define('BASE_HREF', '/adm');


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

$ctrl = 'Utils';
$routes[$ctrl][] = 'tmppanels';

$ctrl = 'Home';
$routes[$ctrl][] = '';
$routes[$ctrl][] = '*';
$routes[$ctrl][] = '*/*';
$routes[$ctrl][] = '*/*/*';
$routes[$ctrl][] = '*/*/*/*';

define('ROUTES', serialize($routes));

