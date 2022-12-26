<?php
namespace Src\repository;

use PDO;


class CommentRepository extends ManagerRepository{ 
        
    /**
     * selectElement
     *
     * @param  mixed $id
     * @return void
     * recuperer un commentaire 
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
     * recuperer les commmentaires des articles 
     */
    public function WhereCommentsArticle(int $article_id): array
    {
        $query = $this->_connexion->prepare("SELECT account.login, comments.content, comments.id 
        FROM comments
        INNER JOIN account
        ON comments.account_id = account.id 
        WHERE comments.article_id = :article_id");
        $query->execute(['article_id' => $article_id]);
        $comments = $query->fetchAll();
        return $comments;
    }

    
    
    /**
     * insertComment
     *
     * @param  mixed $author
     * @param  mixed $content
     * @param  mixed $article_id
     * @return void
     * Inserer commentaire 
     */
    function insertComment(string $account_id, string $content, int $article_id)
    {
        $stmt = $this->_connexion->
        prepare("INSERT INTO comments (account_id, content, article_id, created_date) 
                VALUES( :account_id, :content, :article_id, NOW())");;
        $stmt->execute(compact('account_id', 'content', 'article_id'));
    }
    
    /**
     * deleteElement
     *
     * @param  mixed $id
     * @return void
     * Supprimer commentaire 
     */
    public function deleteComment(int $id): void
    {
        $stmt = $this->_connexion->prepare("DELETE FROM comments WHERE id = :id ");
        $stmt->execute(['id' => $id]);
    }


}