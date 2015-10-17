<?php

define('APP_DIR', 'Projecto/');
define('CLASSES_PREFIX', 'Projecto_');
define('BASE_HREF', '/qpapps/projecto/?uri=');

define('IMAP_HOST', 'imap.quickpanel.sk');
define('IMAP_PORT', '993');
define('IMAP_SSL', '/ssl/novalidate-cert');
define('IMAP_USERNAME', 'info@quickpanel.sk');
define('IMAP_PASSWORD', 'o[jRKpv@n3oFW9VtC');

// ------- routes -----------------------------------------------

$routes = array();

$ctrl = 'Email';
$routes[$ctrl][] = 'email';

$ctrl = 'Project';
$routes[$ctrl][] = 'project';

$ctrl = 'Login';
$routes[$ctrl][] = 'login';

$ctrl = 'Home';
$routes[$ctrl][] = '';
$routes[$ctrl][] = '*';
$routes[$ctrl][] = '*/*';
$routes[$ctrl][] = '*/*/*';
$routes[$ctrl][] = '*/*/*/*';

define('ROUTES', serialize($routes));

