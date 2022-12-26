<?php
namespace Src\core;


use Exception;
use Src\core\Rendering;
use Src\controller\backcontroller\AdminController;
use Src\controller\frontController\{
    AuthController,
    UserController};
use Src\controller\frontController\{
    ArticleController,
    CommentController};

class Router{


    // Router entre les pages de l'application
    static public function run()
    {
        $controllerArticle = new ArticleController();
        $controllerAuth = new AuthController();
        $controllerComment = new CommentController();
        $controllerAdmin = new AdminController();
        $controllerUser= new UserController();
        

        // Si l'utilisateur est loggué
        if (isset($_SESSION['isLogged'])) 
        {

            $page = isset($_GET['page']) ? $_GET['page'] : '';
            // Si l'utilisateur est un admin
            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) 
            {
                
                // Router entre les pages de la partie admin (Backoffice)
                switch($page) 
                {
                    case 'admin':
                    default:

                    $action = isset($_GET['action']) ? $_GET['action'] : '';
                    switch ($action) 
                    {
                        case 'createAccount':
                            $controllerAdmin->createAccount();
                            break;
                        case 'editAccount':
                            $controllerAdmin->editAccount();     
                            break;
                        case 'deleteAccount':
                            $controllerAdmin->deleteAccountAction();
                            break;
                        case 'listAccounts':
                            $controllerAdmin->listAccount();
                            break;
                        case 'listArticle':
                            $controllerAdmin->listArticle();
                            break;                         
                        case 'addArticle':
                            $controllerArticle->addArticle();
                            break;                  
                        case 'deleteArticle':
                            $controllerArticle->deleteArticle();
                            break;
                        case 'modifyArticle':
                                $controllerArticle->editArticle();
                            break;                        
                        case 'displayArticle':
                                $controllerArticle->displayArticle();
                            break;
                            break;                        
                        case 'addComment':
                                $controllerComment->insertComment();
                            break;
                        case 'deleteComment':
                            $controllerComment->deleteComment();
                            break; 
                        case 'deconnexion':
                                $controllerAuth->logOut();
                                
                            break;
                        default:
                            
                                $controllerAdmin->displayAdminHome();

                            break;
                    }
                        
                }
            
            } 
            else 
            {
                // Router entre les pages de la partie utilisateur non admin (Frontoffice)
                switch($page) 
                {

                    case 'user':
                    default:
                    try{
                        $action = isset($_GET['action']) ? $_GET['action'] : '';
                        switch ($action) 
                        {

                            case 'ajoutArticle':
                                $controllerUser->addArticle();
                                break;
                            case 'ajoutComment':
                                $controllerUser->insertComment();
                                break;
                            case 'showArticle':
                                $controllerUser->showArticle();
                                break;
                            case 'deconnexion':
                                $controllerAuth->logOut();
                                break;
                            default:

                                $controllerUser->displayHome();
                                break;
                        }
                    }catch(Exception $e)
                    {
                        header('location: ../view/404.phtml');
                    }

                }    
            }
            exit();
        }
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        switch($page)
        {
           case 'connexion':
            $controllerAuth->connexion();
                break;
            default;
            $controllerAuth->register();
                break;
        }
        
    }
}
