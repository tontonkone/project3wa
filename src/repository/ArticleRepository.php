<?php
namespace Src\repository;

use PDO;
use Src\model\ArticleModel;

class ArticleRepository {


    private PDO $_connexion;

    public function __construct()
    {
        $this->_connexion = DataBase::getConnexion();
    }

    /**
     * ****************************SELECT_ELEMENTS ****************************
     * ************************************************************************
     */
    public function selectAllElements(): array
    {
        
        $sql = "SELECT * FROM articles";
        $resultats = $this->_connexion->query($sql);
        $articles = $resultats->fetchAll(PDO::FETCH_CLASS , 'Src\model\ArticleModel');
        return $articles;
    }
    /**
     * ****************************SELECT_ELEMENT_ID **************************
     * ************************************************************************
     */

    public function selectElement(int $id)
    {
        $query = $this->_connexion->prepare("SELECT * FROM articles WHERE id = :id");
        $query->execute(['id' => $id]);
        $element = $query->fetch();
        return $element;
    }

    public function deleteElement(int $id): void
    {
        $query = $this->_connexion->prepare("DELETE FROM articles WHERE id = :id ");
        $query->execute(['id' => $id]);
    }


}