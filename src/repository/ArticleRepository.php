<?php
namespace Src\repository;

use PDO;
use PDOException;
use Src\core\Debug;
use Src\model\ArticleModel;
use Src\controller\frontController\ArticleController;

class ArticleRepository extends ManagerRepository {
    

    function insertComment(string $author, string $content, int $article_id)
    {
        $stmt = $this->_connexion->prepare('INSERT INTO articles SET author = :author, comment = :comment, post_id = :post_id');
        $stmt->execute(compact('author', 'content', 'article_id'));
    }

    
    /**
     * selectionner les article 
     * @return array
     */
    public function selectAllArticles(): array
    {
        
        $sql = "SELECT account.* , articles.* 
                FROM account 
                JOIN articles 
                ON account.id = articles.author_id ";
        $stmt= $this->_connexion->query($sql);
        $articles = $stmt->fetchAll(PDO::FETCH_CLASS ,'Src\model\ArticleModel');
        return $articles;
    }

    
    /**
     * selectElement
     *
     * @param  mixed $id
     * @return void
     *  ****************************SELECT_ELEMENT_ID **************************
     */
    public function selectElement(int $id)
    {
        $stmt = $this->_connexion->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $element = $stmt->fetch();
        return $element;
    }

    public function whereArticle(int $article_id): array
    {
        $query = $this->_connexion->prepare("SELECT account.login, account.photo, articles.*
                                                FROM articles
                                                INNER JOIN account
                                                ON articles.author_id = account.id 
                                                WHERE articles.id = :article_id");
        $query->execute(['article_id' => $article_id]);
        $articles = $query->fetchAll();
        return $articles;
    }

    public function whereArticlLog(): array
    {
        $query = $this->_connexion->prepare("SELECT account.*, articles.*
                                                FROM articles
                                                INNER JOIN account
                                                ON articles.author_id = account.id
                                                ORDER BY articles.created_date DESC ");
        $query->execute();
        $articles = $query->fetchAll();
        return $articles;
    }
    
    /**
     * deleteElement
     *
     * @param  mixed $id
     * @return void
     * *****************************DELETE_ARTICLE *******************************
     */
    public function deleteElement(int $id): void
    {
        $stmt = $this->_connexion->prepare("DELETE FROM articles WHERE id = :id ");
        $stmt->execute(['id' => $id]);
    }


    public function createArticle(string $title,  string $content, string $author_id, $files): void
    {
        $query = $this->_connexion->prepare("INSERT INTO articles (title, content, author_id, files, created_date) 
                                            VALUES( :title, :content, :author_id, :files, NOW())");
        $query->execute(compact('title', 'content', 'author_id','files'));
    }
    
    public function editArticle(string $title, string $content, int $article_id): void
    {
        $query = $this->_connexion->prepare("UPDATE articles SET title = ?, content = ?, created_date = NOW() WHERE id = ?");
        $query->execute(array($title,$content, $article_id));
    }

}