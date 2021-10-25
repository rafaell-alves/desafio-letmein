<?php

namespace App\Route;
include 'api.php';
use Api;


class Routes
{
    private static $api;
    function __construct()
    {
        self::$api = new Api;
    }

    public function callRoutes($uri)
    {
        $uri = explode('/', $uri);
        if ($uri[1] == '' || $uri[1] == "home") {
            include 'App/views/home.html';
         
        }else if ($uri[1] == 'api') {   
            
            self::$api->callEndpoint($uri[2]);
        }else{
            include 'App/views/404-page.html';
        }
        
    }
}
