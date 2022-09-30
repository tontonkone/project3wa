<?php
namespace Src\controller\frontController;



use Src\core\Rendering;
use Src\repository\ArticleRepository;
use Src\repository\CommentRepository;

class ArticleController{
    protected $model;
    public function __construct()
    {
        $this->model = new ArticleRepository(); 
    }
    /**
     * *********************** INDEX PAGE *************************
     **************************************************************
     */
    public function index()
    {
        //montrer la liste des articles 
        $articles = $this->model->selectAllElements();
        $pageTitle = "Accueil";
        Rendering::renderContent('listarticle', compact('pageTitle', 'articles'));
    }
    
    public function displayArticle()
    {

        $commentModel = new CommentRepository();
        $article_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) 
        {
            $article_id = $_GET['id'];
        }
        // On peut désormais décider : erreur ou pas ?!
        if (!$article_id) 
        {
            die("Vous devez préciser un paramètre `id` dans l'URL !");
        }
        $article = $this->model->selectElement($article_id);

        $commentaires = $commentModel->WhereCommentsArticle($article_id);
        $pageTitle = $article['title'];
        rendering::renderContent('articles/show', compact('pageTitle','article', 'comments','post_id'));

    }
    /**
     * *********************DELETE_ARTICLE****************************
     * ***************************************************************
     */
    public function deleteArticle(): void
    {
        if (empty($_GET['id']) || !ctype_digit($_GET['id'])) 
        {
            die("Ho ?! Tu n'as pas précisé l'id de l'article !");
        }
        $id = $_GET['id'];
        $article = $this->model->selectElement($id);
        if (!$article) 
        {
            die("L'article $id n'existe pas, vous ne pouvez donc pas le supprimer !");
        }
        $this->model->deleteElement($id);
        
        Rendering::renderContent('admin/adminPage');
    }

}