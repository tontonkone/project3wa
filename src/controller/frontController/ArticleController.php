<?php
namespace Src\controller\frontController;

use Src\controller\HomeController;
use Src\core\Debug;
use Src\core\Url;
use Src\core\Rendering;
use Src\model\ArticleModel;
use Src\model\CommentModel;
use Src\repository\ArticleRepository;
use Src\repository\CommentRepository;

class ArticleController {
    

    public function __construct()
    {
        $this->articleRepository = new ArticleRepository();
        $this->commentRepository = new CommentRepository();
        $this->articleModel = new ArticleModel();
        $this->commentModel = new CommentModel();
    }

    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //montrer la liste des articles 
        $articles = $this->ArticleModel->selectAllElements();
        $pageTitle = "Accueil";
        Rendering::renderContent('listarticle', compact('pageTitle', 'articles'));
    }

    public function addArticle()
    {
        if(isset($_POST)){
            $errors = array();
            if(!empty($_POST)){
                
                if(empty($title = htmlspecialchars($_POST['title']))){
                    $errors['title'] = 'Vous devez ajouter un titre';
                }

                if(empty($content = htmlspecialchars($_POST['content']))){
                    $errors['content'] = 'Vous devez ajouter un contenu';
                }

                $author_id = htmlspecialchars($_POST['author_id']);
                
                if(empty($errors)){
                    /** Insertion de l'Article*/
                    $this->articleRepository->createArticle($title, $content, $author_id);

                    Rendering::renderContent('admin/adminPage');
                    exit;
                }
                //\Utils::debug($errors); 
            }
        }
        /** Affichage*/
        $pageTitle = "Créer un Article";
        
    Rendering::renderContent('admin/addArticle', compact('pageTitle', 'errors'));
    
    
    }

    public function displayArticle()
    {

        $article_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) 
        {
            $article_id = $_GET['id'];
        }
        // On peut désormais décider : erreur ou pas ?!
        if (!$article_id) 
        {
            die("you are lost lol !");
        }
        // on va chercher l'article 
        $article = $this->ArticleModel->selectElement($article_id);

        // on va chercher les commentaire
        $commentaires = $this->commentModel->WhereCommentsArticle($article_id);
        $pageTitle = $article['title'];
        rendering::renderContent('articles/show', compact('pageTitle','article', 'commentaires','post_id'));

    }
    /**
     * *********************DELETE_ARTICLE****************************
     * ***************************************************************
     */
    public function deleteArticle(): void
    {
        if (empty($_GET['id']) || !ctype_digit($_GET['id'])) 
        {
            Rendering::renderContent('admin/adminPage');
        }
        $id = $_GET['id'];
        $article = $this->articleRepository->selectElement($id);
        if (!$article) 
        {
            die("L'article $id indefini !");
        }
        $this->articleRepository->deleteElement($id);
        
        Rendering::renderContent('admin/adminPage');
    }

}