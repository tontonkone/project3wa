<?php
namespace Src\repository;

use PDO;
use Src\model\AccountModel;
use Src\model\ArticleModel;
use Src\repository\DataBase;

abstract class ManagerRepository{

    protected $_connexion;

    public function __construct()
    {
        $this->_connexion = DataBase::getConnexion();
    }

}