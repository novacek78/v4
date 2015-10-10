<?php

class Router
{

    /**
     * Router zisti podla dopytovanej URI ktory controller treba na obsluzenie - vrati jeho meno
     *
     * @return string
     */
    public static function run() {

        $controllerName = '';

        // vytiahneme z URL parameter 'uri', kde su ulozene vsetky slug parametre requestu
        $uri = strtolower(Request::getParam('uri', 'string'));

        $routes = unserialize(ROUTES);

        foreach ($routes as $ctrlName => $ctrlRoutes) {
            foreach ($ctrlRoutes as $route) {

                // vyescapujeme lomitka
                $pattern = "/^" . str_replace('/', '\/', $route) . "$/";
                // nahradime * za regex tvar
                $pattern = str_replace('*', '[a-z0-9]+', $pattern);

                if (preg_match($pattern, $uri) === 1) {
                    // URI sedi na tuto routu !
                    $controllerName = $ctrlName;
                    break 2;
                }
            }
        }

        return $controllerName;
    }
}