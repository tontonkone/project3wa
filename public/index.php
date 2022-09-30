<?php

use Src\core\Router;
require dirname(__DIR__) ."/config/init.php";
require dirname(__DIR__) . "/vendor/autoload.php";



$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
/* echo '<pre>';
print_r(MODEL);
echo '<pre>';
print_r(CONTROLLER);
echo '<pre>';
print_r(VIEW);
echo '<pre>';
print_r(HOST);
exit;
 */
session_start();

Router::run();



