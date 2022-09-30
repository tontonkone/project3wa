<?php
namespace Src\repository;

use Src\model\ArticleModel;
use PDO;

abstract class ManagerRepository{
    
    private $table;
    private PDO $_connexion;

    public function __construct()
    {
        $this->_connexion = DataBase::getConnexion();
    }

}