<?php
namespace Src\repository;

use PDO;
use Src\model\ArticleModel;

abstract class General{
    private PDO $_connexion;

    public function __construct()
    {
        $this->_connexion = DataBase::getConnexion();
    }


    public function deleteElement(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->elTable} WHERE id = :id ");
        $query->execute(['id' => $id]);
    }

}