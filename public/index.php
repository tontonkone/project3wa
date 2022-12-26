<?php
session_start();

use Src\core\Router;
require dirname(__DIR__) ."/config/init.php";
require dirname(__DIR__) . "/vendor/autoload.php";



Router::run();



