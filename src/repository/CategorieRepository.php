<?php
namespace Src\repository;

use PDO;
use Src\model\CategorieModel;

class CategorieRepository extends ManagerRepository{

    public function insertCategorie(CategorieModel $category)
    {
        $stmt = $this->_connexion->prepare('INSERT INTO category (name) VALUES(:name');
        $stmt->execute(array('name' => $category->getname()));
        return $stmt;
    }
}