<?php
namespace Src\repository;

use PDO;


class CommentRepository {


    private PDO $_connexion;

    public function __construct()
    {
        $this->_connexion = DataBase::getConnexion();
    }
    /**
     * ****************************SELECT_ELEMENT_BY_ID ***********************
     * ************************************************************************
     */
    public function selectElement(int $id)
    {
        $query = $this->_connexion->prepare("SELECT * FROM comments WHERE id = :id");
        $query->execute(['id' => $id]);
        $element = $query->fetch();
        return $element;
    }

    /**
     * ****************************WHERE_ELEMENT *****************************
     * ************************************************************************
     */
    public function WhereCommentsArticle(int $article_id): array
    {
        $query = $this->_connexion->prepare("SELECT * FROM comments WHERE article_id = :article_id");
        $query->execute(['article_id' => $article_id]);
        $commentaires = $query->fetchAll();

        return $commentaires;
    }
    /**
     * ****************************INSERT_ELEMENT *****************************
     * ************************************************************************
     */

    function insertComment(string $author, string $content, int $article_id)
    {
        $query = $this->_connexion->prepare('INSERT INTO comments SET author = :author, comment = :comment, post_id = :post_id');
        $query->execute(compact('author', 'content', 'article_id'));
    }
    /**
     * ****************************DELETE_ELEMENT *****************************
     * ************************************************************************
     */


    public function deleteElement(int $id): void
    {
        $query = $this->_connexion->prepare("DELETE FROM {$this->elTable} WHERE id = :id ");
        $query->execute(['id' => $id]);
    }


}