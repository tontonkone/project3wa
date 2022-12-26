<?php
namespace Src\controller\frontController;

use Src\controller\backcontroller\AdminController;
use Src\core\Secu;
use Src\core\Debug;
use Src\core\Rendering;
use Src\controller\HomeController;

class ArticleController extends HomeController{
    

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * addArticle
     *
     * @return void
     * ajouter un articles du coter de l'admin
     */
    public function addArticle()
    {

       if(isset($_POST['ajoutArticle']))
        {
            $errors = array();
            if(!empty($_POST))
            {
                $title = Secu::inputValid($_POST['title']);
                $content = Secu::inputValid($_POST['content']);
                $author_id = Secu::inputValid($_POST['author_id']);
                
                if(empty($title))
                {
                    $errors['title'] = 'Vous devez ajouter un titre';
                }

                if(empty($content))
                {
                    $errors['content'] = 'Vous devez ajouter un contenu';
                }
            
                $imageName = $_FILES['image']['name']; // le nom du fichier 
                $imageTmp = $_FILES['image']['tmp_name']; // le nom temporaire du fichier
                $imageSize = $_FILES['image']['size']; // la taille de l'image

                $imageExplode = explode('.', $imageName);
                /** on genere un nom unique  */
                $newNameImage = uniqid('post').'.' . end($imageExplode);
                $newNameImagePath = '../public/assets/img/img_article/' . $newNameImage;
                /** DEF: strrchr Trouve la dernière occurrence d'un caractère dans une chaîne */
                $imageExtension = strrchr($newNameImage, ".");
                $extensionArray = array('.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
                $sizeMax = 2000000;

                if (!empty($_FILES)) 
                {
                    if ($imageSize > $sizeMax) 
                    {
                        $errors['errorImage'] = "La taille de votre image ne peut pas depasser 2Mo";
                    }

                    if (!in_array($imageExtension, $extensionArray) && $imageSize !== 0) 
                    {
                        $errors['errorImage'] = "Vous ne pouvez télécharger que des fichiers jpg, jpeg ou png ";
                    }
                    if ($imageSize === 0) {
                        $newNameImagePath = "";
                    }

                    if (empty($errors)) {
                        /** Insertion de l'Article*/
                        $this->articleRepository->createArticle($title, $content, $author_id, $newNameImagePath);
                        move_uploaded_file($imageTmp, $newNameImagePath);
                        header('location: ?');
                    }

                }
            }
        }
        $articles = $this->articlesRepository->whereArticlLog();
        Rendering::renderContent('admin/adminPage', compact('articles','errors') );
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
            header('location: ?');
        }
        $article = $this->articleRepository->whereArticle($article_id);
        $commentaires = $this->commentRepository->WhereCommentsArticle($article_id);
       
        rendering::renderContent('admin/showArticle', compact('article', 'commentaires'));

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
            header('location: ?');
        }
        $id = $_GET['id'];
        $article = $this->articleRepository->selectElement($id);
        if (!$article) 
        {
            header('location: ?');
        }
        $this->articleRepository->deleteElement($id);
        
        header('location: ?');
    }

    public function editArticle()
    {
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) 
        {
            $article_id = $_GET['id'];
        }
        if (!$article_id) 
        {
            header('location: ?');
        }

        $article = $this->articleRepository->selectElement($article_id);
        if (isset($_POST)) 
        {
            $errors = array();
            if (!empty($_POST)) 
            {
                $title = Secu::inputValid($_POST['title']);
                $content = Secu::inputValid($_POST['content']);

                if (empty($title)) 
                {
                    $errors['title'] = 'Veuillez modifier ou laisser le titre par défaut';
                }
                if (empty($content)) 
                {
                    $errors['content'] = 'Veuillez modifier le contenu ou laisser le contenu par defaut';
                }
                if (empty($errors)) 
                {
                    $this->articleRepository->editArticle($title,$content, $article_id);
                    header('location: ?');
                }
            }
        }

        $pageTitle = 'Modifier votre Article';

        Rendering::renderContent('admin/editArticle', compact('pageTitle', 'article', 'article_id', 'errors'));
    }
    
}