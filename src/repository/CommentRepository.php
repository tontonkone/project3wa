<?php
namespace Src\repository;

use PDO;


class CommentRepository extends ManagerRepository{ 
        
    /**
     * selectElement
     *
     * @param  mixed $id
     * @return void
     * recuperer un commentaire *************************************************
     */
    public function selectComment(int $id)
    {
        $stmt = $this->_connexion->prepare("SELECT * FROM comments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $comment = $stmt->fetch();
        return $comment;
    }

    /**
     * WhereCommentsArticle
     *
     * @param  mixed $article_id
     * @return array
     * recuperer les commmentaires des articles ************************************
     */
    public function WhereCommentsArticle(int $article_id): array
    {
        $query = $this->_connexion->prepare("SELECT account.login, comments.content, comments.id 
        FROM comments
        INNER JOIN account
        ON comments.account_id = account.id 
        WHERE comments.article_id = :article_id");
        $query->execute(['article_id' => $article_id]);
        $commentaires = $query->fetchAll();
        return $commentaires;
       /*  $stmt = $this->_connexion->prepare("SELECT * FROM comments WHERE article_id = :article_id");
        $stmt->execute(['article_id' => $article_id]);
        $commentaires = $stmt->fetchAll(); */

        return $commentaires;
    }
    
    /**
     * insertComment
     *
     * @param  mixed $author
     * @param  mixed $content
     * @param  mixed $article_id
     * @return void
     * Inserer commentaire ************************************************************
     */
    function insertComment(string $author, string $content, int $article_id)
    {
        $stmt = $this->_connexion
        ->prepare('INSERT INTO comments 
                    SET author = :author, comment = :comment, post_id = :post_id');
        $stmt->execute(compact('author', 'content', 'article_id'));
    }
    
    /**
     * deleteElement
     *
     * @param  mixed $id
     * @return void
     * Supprimer commentaire **************************************************
     */
    public function deleteElement(int $id): void
    {
        $stmt = $this->_connexion->prepare("DELETE FROM comments WHERE id = :id ");
        $stmt->execute(['id' => $id]);
    }


}