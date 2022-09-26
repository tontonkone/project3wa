<?php
namespace Src\repository;

use PDO;
use PDOException;

class DataBase {
    // DataBase instance singleton
    private static DataBase $dataBaseInstance;

    // DataBase properties
    private PDO $_connexion;
    private const DATABASE_CONFIG_FILEPATH = 'src/database.config.php';

    private function __construct(
        array $dbConfig
    ) {
        $dsn = 'mysql:dbname=' . $dbConfig['dbname'] . ';host=' . $dbConfig['host'] . ';charset=utf8';

        try {
            $this->_connexion = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
        }
        catch(PDOException $exception) {
            $message = $exception->getMessage();
            //TODO à virer quand on aura accès aux logs de PHP
            echo "Erreur de connexion à la BDD : $message";
            error_log("Échec de la connexion à la base de données : $message\n");
            exit();
        }
    }

    public static function getConnexion():PDO {
        if (!isset(self::$dataBaseInstance)) {
            if (!file_exists(self::DATABASE_CONFIG_FILEPATH)) {
                throw new \Exception('Could not find '. self::DATABASE_CONFIG_FILEPATH . ' file.');
            }
            $dbConfig = require self::DATABASE_CONFIG_FILEPATH;
            self::$dataBaseInstance = new DataBase($dbConfig);
        }
        return self::$dataBaseInstance->_connexion;
    }
}
