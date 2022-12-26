<?php
namespace Src\controller\frontController;

use Src\controller\HomeController;
use Src\core\Debug;
use Src\core\Url;
use Src\core\Rendering;
use Src\core\Secu;
use Src\repository\ArticleRepository;
use Src\repository\CommentRepository;

use function Termwind\render;

class CommentController extends HomeController{
    

    public function __construct()
    {
        parent::__construct();
    }

        
    /**
     * insert
     *
     * @return void
     * inserer commentaire 
     * 
     */

    public function insertComment()
    {
        $msgErrors= array();
        $author=null;
        if (! empty($_POST['author'])) 
        {
            $author = $_POST['author'];
        }
        $content = null;
        if (! empty($_POST['content'])) 
        {
            $content = Secu::inputValid($_POST['content']);
        }
        $article_id = null;
        
        if (! empty($_POST['article_id']) && ctype_digit($_POST['article_id'])) 
        {
            $article_id = $_POST['article_id'];
        }
        if (!$author || !$article_id || !$content) {
            $msgErrors['content'] = "une erreur s'est produite veillez verifier le formulaire ";
        }
        // si erreur est different de vide on insert dans la bd
        if ( empty($msgErrors))
        {
            $this->commentRepository->insertComment($author, $content, $article_id);

        }
        $article = $this->articleRepository->whereArticle($article_id);
        $commentaires = $this->commentRepository->WhereCommentsArticle($article_id);

        Rendering::renderContent('admin/showArticle', compact('article', 'commentaires','msgErrors'));
    }
    
    /**
     * delete
     *
     * @return void
     * effacer commentaire 
     */

    public function deleteComment()
    {
        //si id comment est vide ou c'est pas un nombre 
        if (empty($_GET['id']) || !ctype_digit($_GET['id'])) 
        {
            //retour sur page accueil
            header('location: ?');
        }
        else
        {
            $id = $_GET['id'];
            $commentaire = $this->commentRepository->selectComment($id);
            if (!$commentaire) {
                header('location: ?');
            }

            $this->commentRepository->deleteComment($id);

            header('location: ?');
        }
        header('location: ?');
    }
}