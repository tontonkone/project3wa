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
     * rendre la vue de la page d'acceuil
     */
    public function index()
    {
        //montrer la liste des articles 
        $articles = $this->ArticleModel->selectAllElements();
        $pageTitle = "Accueil";
        Rendering::renderContent('listarticle', compact('pageTitle', 'articles'));
    }
    
    /**
     * addArticle
     *
     * @return void
     * ajouter un articles du coter de l'admin
     */
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
    
    /**
     * displayArticle
     *
     * @return void
     * afficher les articles 
     */
    public function displayArticle()
    {

        $article_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) 
        {
            $article_id = $_GET['id'];
            
        }
        if (!$article_id) 
        {
            die("you are lost lol !");
        }
        $article = $this->articleRepository->selectElement($article_id);
        
        $commentaires = $this->commentRepository->WhereCommentsArticle($article_id);
        $pageTitle = $article['title'];
        rendering::renderContent('admin/showArticle', compact('pageTitle','article', 'commentaires'));

    }
    
    /**
     * deleteArticle
     *
     * @return void
     * 
     * @method pour effacer les articles appeller par le
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

    public function editArticle()
    {
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $article_id = $_GET['id'];
        }
        if (!$article_id) 
        {
            die("Vous devez préciser un paramètre `id` dans l'URL !");
        }
            $article = $this->articleRepository->selectElement($article_id);
            if (isset($_POST)) 
            {
                $errors = array();
                if (!empty($_POST)) 
                {
                    if (empty($title = htmlspecialchars($_POST['title']))) 
                    {
                        $errors['title'] = 'Veuillez modifier ou laisser le titre par défaut';
                    }
                    if (empty($content = htmlspecialchars($_POST['content']))) 
                    {
                        $errors['content'] = 'Veuillez modifier le contenu ou laisser le contenu par defaut';
                    }
                    if (empty($errors)) 
                    {
                        $article = $this->articleRepository->editArticle($title,$content, $article_id);
                        Rendering::renderContent('admin/adminPage');
                        exit;
                    }
                }
            }

        $pageTitle = 'Modifier votre Article';

        Rendering::renderContent('admin/editArticle', compact('pageTitle', 'article', 'article_id', 'errors'));
    }



}