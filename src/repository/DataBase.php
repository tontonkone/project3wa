<?php
namespace Src\repository;

use PDO;
$_connexion = new Database();

class Database
{


    public static function getConnexion(): PDO
    {
        $_connexion = new PDO('mysql:host=localhost;dbname=social;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC

        ]);
        return $_connexion;
    }
}
