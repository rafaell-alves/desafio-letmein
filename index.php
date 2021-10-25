<?php

require_once "routes.php";

use \App\Route\Routes;


$route = new Routes;

$route->callRoutes($_SERVER['REQUEST_URI']);
