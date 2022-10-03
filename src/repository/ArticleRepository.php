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
     * selectAllElements
     *
     * @return array
     * ****************************SELECT_ELEMENTS ****************************
     */
    public function selectAllArticles(): array
    {
        
        $sql = "SELECT * FROM article";
        $stmt= $this->_connexion->query($sql);
        $articles = $stmt->fetchAll(PDO::FETCH_CLASS , 'Src\model\ArticleModel');
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
        $stmt = $this->_connexion->prepare("SELECT * FROM article WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $element = $stmt->fetch();
        return $element;
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
        $stmt = $this->_connexion->prepare("DELETE FROM article WHERE id = :id ");
        $stmt->execute(['id' => $id]);
    }


    public function createArticle(string $title,  string $content, $author_id): void
    {
        $query = $this->_connexion
        ->prepare("INSERT INTO article (title, content, account_id, created_date) 
        VALUES( :title, :content, :author_id, NOW())");
        $query->execute(compact('title', 'content', 'author_id'));
    }




}