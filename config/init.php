<?php

$root = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];

// http://hosts/projet3wa/

define('HOST', 'http://' . $host . '/projet3watest1/');

// C:/wamp64/www/site/projet3wa/
define('ROOT', $root . '/projet3watest1/'); 

// C:/wamp64/www/site/projet3wa/src/core/
define('CORE', ROOT . 'src/core/'); 

// C:/wamp64/www/site/projet3wa/src/controller/
define('CONTROLLER', ROOT . 'src/controller/');

// http://hosts/projet3wa/public/assets/
define('MODEL', ROOT . 'src/model/');

//C:/wamp64/www/site/projet3wa/src/view/
define('VIEW', ROOT . 'src/view/');

// http://hosts/projet3wa/public/assets/
define('PUBLIC_HOME', HOST . 'public/assets/');
define('PUBLIC_INDEX', HOST . 'public');

define('ADMIN_DEL_ART', '?page=admin&action=deleteArticle&id=');
define('ADMIN_DEL_COM', '');
define('ADMIN_DEL_ACCOUNT', '');
define('ADMIN_ADD_ACCOUNT', '');
define('ADMIN_ADD_ART', 'd');
define('ADMIN_ADD_COM', 'd');