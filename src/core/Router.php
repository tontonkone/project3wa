<?php
namespace Src\core;


use Src\core\Rendering;
use Src\controller\HomeController;
use Src\controller\frontController\AuthController;
use Src\controller\frontController\ArticleController;
use Src\controller\frontController\CommentController;

class Router{

    // Router entre les pages de l'application
    static public function run()
    {
        $controllerArticle = new ArticleController;
        $controllerAuth = new AuthController();
        $controller = new HomeController;
        $controllerComment = new CommentController();
        // Si l'utilisateur est loggué
        if (isset($_SESSION['isLogged'])) 
        {

            $page = isset($_GET['page']) ? $_GET['page'] : '';
            // Si l'utilisateur est un admin
            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) {
                
                // Router entre les pages de la partie admin (Backoffice)
                switch($page) {
                    case 'admin':
                    default:

                    $action = isset($_GET['action']) ? $_GET['action'] : '';
                    switch ($action) 
                    {
                        case 'createAccount':
                            $controller->createAccount();
                            break;
                        case 'editAccount':
                            $controller->editAccount();     
                            break;
                        case 'deleteAccount':
                            $controller->deleteAccountAction();
                            break;
                        case 'listAccounts':
                            $controller->listAccount();
                            break;
                        case 'listArticle':
                            $controller->listArticle();
                            break;                         
                        case 'addArticle':
                            $controllerArticle->addArticle();
                            break;                  
                        case 'deleteArticle':
                            $controllerArticle->deleteArticle();;
                            break;
                        case 'modifyArticle':
                                $controllerArticle->editArticle();;
                                break;                        
                        case 'displayArticle':
                                $controllerArticle->displayArticle();;
                                break;
                        case 'deconnexion':
                                $controller->logOut();;
                            break;
                        case '':
                                /* $controllerComment->delete();;
                            break; */
                                Rendering::renderContent('admin/adminPage');
                        }
                        
                }
            
            } else {
                // Router entre les pages de la partie utilisateur non admin (Frontoffice)
                switch($page) {
                    case 'home':
                    default:
                    //sur page d'acceuil
                        $controller->displayHome();
                        break;
                }    
            }
            exit();
        }

    // Formulaires de login et d'inscription
    $controllerAuth->authVerif();
    }
}
