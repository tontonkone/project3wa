<?php
namespace Src\controller\frontController;

use Src\core\Secu;
use Src\core\Rendering;
use Src\controller\HomeController;

class UserController extends HomeController{

/* 
    public function displayHome()
    {
        $articles = $this->articlesRepository->selectAllElements();
        $titleH1 = "nouveaux blog ";
        $title = "Accueil";
        Rendering::renderContent("homePage", compact("title", 'titleH1', 'articles'));
    } */


    /**
     * displayHome
     *
     * @return void
     * afficher la home page au visiteur
     */
    public function displayHome()
    {
        $articles = $this->articlesRepository->whereArticlLog();
        /*    Debug::run($articles); */
        $title = "Accueil";
        Rendering::renderContent("user/homePage", compact("title", "articles"));
    }
    
    /**
     * showArticle
     *
     * @return void
     */

    public function showArticle()
    {

        $article_id = null;
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $article_id = $_GET['id'];
        }
        if (!$article_id) {
            header('location: ?');
        }
        $article = $this->articleRepository->whereArticle($article_id);
        $commentaires = $this->commentRepository->WhereCommentsArticle($article_id);

        rendering::renderContent('user/showArticle', compact('article', 'commentaires'));
    }
    
    /**
     * insertComment
     *
     * @return void
     */

    public function insertComment()
    {
        $msgErrors = array();
        $author = null;
        if (!empty($_POST['author'])) {
            $author = $_POST['author'];
        }
        $content = null;
        if (!empty($_POST['content'])) {
            $content = Secu::inputValid($_POST['content']);
        }
        $article_id = null;

        if (!empty($_POST['article_id']) && ctype_digit($_POST['article_id'])) {
            $article_id = $_POST['article_id'];
        }
        if (!$author || !$article_id || !$content) {
            $msgErrors['content'] = "une erreur s'est produite veillez verifier le formulaire ";
        }
        // si erreur est different de vide on insert dans la bd
        if (empty($msgErrors)) {
            $this->commentRepository->insertComment($author, $content, $article_id);
        }
        $article = $this->articleRepository->whereArticle($article_id);
        $commentaires = $this->commentRepository->WhereCommentsArticle($article_id);

        Rendering::renderContent('user/showArticle', compact('article', 'commentaires', 'msgErrors'));
    }
    
    /**
     * addArticle
     *
     * @return void
     */
    public function addArticle()
    {

        if (isset($_POST['ajoutArticle'])) {
            $errors = array();
            if (!empty($_POST)) {
                $title = Secu::inputValid($_POST['title']);
                $content = Secu::inputValid($_POST['content']);
                $author_id = Secu::inputValid($_POST['author_id']);

                if (empty($title)) {
                    $errors['title'] = 'Vous devez ajouter un titre';
                }

                if (empty($content)) {
                    $errors['content'] = 'Vous devez ajouter un contenu';
                }

                $imageName = $_FILES['image']['name']; // le nom du fichier 
                $imageTmp = $_FILES['image']['tmp_name']; // le nom temporaire du fichier
                $imageSize = $_FILES['image']['size']; // la taille de l'image

                $imageExplode = explode('.', $imageName);
                /** on genere un nom unique  */
                $newNameImage = uniqid('post') . '.' . end($imageExplode);
                $newNameImagePath = '../public/assets/img/img_article/' . $newNameImage;
                /** DEF: strrchr Trouve la dernière occurrence d'un caractère dans une chaîne */
                $imageExtension = strrchr($newNameImage, ".");
                $extensionArray = array('.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
                $sizeMax = 2000000;

                if (!empty($_FILES)) {
                    if ($imageSize > $sizeMax) {
                        $errors['errorImage'] = "La taille de votre image ne peut pas depasser 2Mo";
                    }

                    if (!in_array($imageExtension, $extensionArray) && $imageSize !== 0) {
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
        Rendering::renderContent('user/homePage', compact('articles', 'errors'));
    }
    
}