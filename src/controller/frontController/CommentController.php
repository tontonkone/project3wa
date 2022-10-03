<?php
namespace Src\controller\frontController;

use Src\controller\HomeController;
use Src\core\Url;
use Src\core\Rendering;
use Src\repository\ArticleRepository;
use Src\repository\CommentRepository;

class CommentController extends HomeController{
    
    protected $modelComment;

    public function __construct()
    {
        $this->modelComment = new CommentRepository();
    }

        
    /**
     * insert
     *
     * @return void
     * inserer commentaire ***********************************
     * 
     */

    public function insert()
    {
        $articleModel = new ArticleRepository();
        $author = null;
            if (! empty($_POST['author'])) 
            {
                $author = $_POST['author'];
            }

            $content = null;
            if (! empty($_POST['content'])) 
            {
                $content = htmlspecialchars($_POST['content']);
            }

            $article_id = null;
            if (! empty($_POST['article_id']) && ctype_digit($_POST['article_id'])) 
            {
                $article_id = $_POST['article_id'];
            }
            if (!$author || !$article_id || !$content) {
                die("Votre formulaire a été mal rempli !");
            }

        $article = $articleModel->selectElement($article_id);

        // Si rien n'est revenu, on fait une erreur
        if (!$article) {
            die("Ho ! L'article $article_id n'existe pas boloss !");
        }

        // 3. Insertion du commentaire
        $this -> modelComment -> insertComment($author, $content, $article_id);

        // 4. Redirection vers l'article en question :
        Url::location('article.php?id=' . $article_id);
        // inserer des commmentaires 
    }
    
    /**
     * delete
     *
     * @return void
     * effacer commentaire **************************************************
     */

    public function delete(){
        // suprimer des commentaires
        /**
         * 1. Récupération du paramètre "id" en GET
         */
        if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
            Rendering::renderContent('admin/adminPage');
        }else{
            $id = $_GET['id'];
            $commentaire = $this->model->selectElement($id);
            if (!$commentaire) {
                die("Aucun commentaire n'a l'identifiant $id !");

                Rendering::renderContent('admin/adminPage');
            }

            $article_id = $commentaire['article_id'];

            $this->modelComment->deleteElement($id);
            /**
             * 5. Redirection vers l'article en question
             */
            Url::location('article.php?id=' . $article_id);
        }

        

        /**
         * 3. Vérification de l'existence du commentaire
         */



        $article_id = $commentaire['article_id'];

        $this->model->deleteElement($id);
        /**
         * 5. Redirection vers l'article en question
         */
        Url::location('article.php?id=' . $article_id);
    }
}