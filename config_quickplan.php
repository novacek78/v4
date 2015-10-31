<?php
define('ENV', 'production');

define('APP_DIR', 'Quickplan/');
define('CLASSES_PREFIX', 'Quickplan_');
define('BASE_HREF', '/qpapps/quickplan/?uri=');

define('IMAP_HOST', 'imap.quickpanel.sk');
define('IMAP_PORT', '993');
define('IMAP_SSL', '/ssl/novalidate-cert');
define('IMAP_USERNAME', 'info@quickpanel.sk');
define('IMAP_PASSWORD', 'o[jRKpv@n3oFW9VtC');

define('DB_HOST', 'localhost');
define('DB_ENGINE', 'mysql');
define('DB_NAME', 'quickplan');
define('DB_USER', 'root');
define('DB_PWD', '');

// ------- routes -----------------------------------------------

$routes = array();

$ctrl = 'Email';
$routes[$ctrl][] = 'email';

$ctrl = 'Project';
$routes[$ctrl][] = 'project';

$ctrl = 'Login';
$routes[$ctrl][] = 'login';

$ctrl = 'Logout';
$routes[$ctrl][] = 'logout';

$ctrl = 'Ajax';
$routes[$ctrl][] = 'ajax/*';
$routes[$ctrl][] = 'ajax/*/*';

$ctrl = 'Home';
$routes[$ctrl][] = '';
$routes[$ctrl][] = '*';
$routes[$ctrl][] = '*/*';
$routes[$ctrl][] = '*/*/*';
$routes[$ctrl][] = '*/*/*/*';

define('ROUTES', serialize($routes));

