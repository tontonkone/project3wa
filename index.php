<?php
require __DIR__ . "/vendor/autoload.php";
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
session_start();

require 'src/view/layout.phtml';

// Supprimer les variables de la session (tout en gardant le SESSION ID) : session_unset()
// Supprimer la session : session_destroy()

