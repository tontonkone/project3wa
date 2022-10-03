<?php

namespace Src\controller;


use Src\core\Url;


use Src\core\Rendering;
use Src\model\AccountModel;
use Src\model\ArticleModel;
use Src\repository\AccountRepository;
use Src\repository\ArticleRepository;

class HomeController
{

    public function __construct()
    {
        $this->articlesRepository = new ArticleRepository();
        $this->accountRepository = new AccountRepository();
        $this->articleModel = new ArticleModel();
        $this->accountModel = new AccountModel();
    }
    /**Create Article */

    /**
     * displayHome
     *
     * @return void
     * afficher la home page au visiteur
     */
    public function displayHome()
    {
        $articles = $this->articlesRepository->selectAllArticles();

        $titleH1 = "nouveaux blog ";
        $title = "Accueil";
        Rendering::renderContent("homePage", compact("title", 'titleH1', 'articles'));
    }

    
    /**
     * listArticle
     *
     * @return void
     * afficher la page de la liste des articles au visiteur 
     */
    public function listArticle()
    {
        $articles = $this->articlesRepository->selectAllArticles();
        $titleH1 = "nouveaux blog ";
        $title = "Accueil";
        Rendering::renderContent("admin/listArticle", compact("title", 'titleH1', 'articles'));
    }

    
    /**
     * retrieveAccountFromRequest
     *
     * @param  mixed $accountRepository
     * @return AccountModel
     * 
     * recuperer l'id  des accounts 
     */
    static public function retrieveAccountFromRequest(AccountRepository $accountRepository): ?AccountModel
    {
        if (!empty($_GET['id'])) {
            return $accountRepository->getAccount(htmlentities($_GET['id']));
        }

        return null;
    }
    /**
     * Vérifie les champs du formulaire et met à jour les propriétés du compte
     * 
     * @param Account $account - Compte à créer / mettre à jour
     * 
     * @return array Liste des erreurs s'il y en a (tableau vide non)
     * 
     */

    /**
     * ***************** CHECK_ACCOUNT **************************
     * **********************************************************
     */
    static public function checkAndFillAccount(AccountModel $accountModel): array
    {
        $errors = [
            'requiredFirstName' => '',
            'requiredLastName' => '',
            'requiredLogin' => '',
            'requiredPassword' => '',
        ];

        $errors['requiredFirstName'] = $accountModel->setFirstName(
            htmlentities($_POST['firstName'])
        );
        $errors['requiredLastName'] = $accountModel->setLastName(
            htmlentities($_POST['lastName'])
        );
        $errors['requiredLogin'] = $accountModel->setLogin(
            htmlentities($_POST['login'])
        );
        if (!(empty($_POST['password']) && $accountModel->hasPassword())) {
            $errors['requiredPassword'] = $accountModel->setPassword(
                $_POST['password']
            );
        }

        if (isset($_POST['isAdmin'])) {
            $accountModel->setIsAdmin(true);
        } else {
            $accountModel->setIsAdmin(false);
        }

        // Filter errors array : keep only not empty values
        $errors = array_filter($errors, function ($v) {
            return !empty($v);
        });

        return $errors;
    }
    
    /**
     * editAccount
     *
     * @return void
     */
    public function editAccount()
    {
        // Récupère le compte depuis la BDD
        $account = self::retrieveAccountFromRequest($this->accountRepository);

        // S'il ne l'a pas trouvé, redirige l'utilisateur vers la page d'accueil
        if (empty($account)) {
            header('Location:?');
            exit();
        }
        // Processus de soumission du formulaire d'édition
        $userMessages = [];
        if (isset($_POST['accountSubmit'])) {
            // Vérifie les champs et met à jour les propriétés du compte
            $userMessages = self::checkAndFillAccount($account);

            // Si le formulaire est valide, met à jour en BDD
            // et redirige l'utilisateur vers la page d'accueil
            if (empty($userMessages)) {
                $this->accountRepository->updateAccount($account);
                header('Location:?');
            }
        }

        // Converti l'objet Account en tabeau associatif
        // pour l'affichage des valeurs dans le formulaire
        $data = $account->toAssociativeArray();
        Rendering::renderContent('admin/accountForm', compact('data', ));
    }
    
    /**
     * createAccount
     *
     * @return void
     */
    
    public function createAccount()
    {
        // Processus de soumission du formulaire de création
        $userMessages = [];
        if (isset($_POST['accountSubmit'])) {
            // Vérifie les champs et met à jour les propriétés du compte
            $userMessages = self::checkAndFillAccount($this->accountModel);

            // Si le formulaire est valide, l'enregistre en BDD
            // et redirige l'utilisateur vers la page d'accueil
            if (empty($userMessages)) {
                $this->accountRepository->createAccount($this->accountModel);
                header('Location:?');
            }
            else
            {
                Rendering::renderContent('admin/accountForm', compact('userMessages'));
                exit;
            }
        }

        // Reaffecte les valeurs saisies par l'utilisateur
        // avant qu'il soumettre le formulaire
        $data = $_POST;
        Rendering::renderContent('admin/accountForm', compact('data'));
    }

    
    /**
     * listAccount
     *
     * @return void
     */
    public function listAccount()
    {
        $accounts = $this->accountRepository->listAccounts();
        $titleH1 = "Liste des Utilisateurs";
        $title = "Utilisateur";
        Rendering::renderContent('admin/listAccounts', compact('title', 'titleH1', 'accounts'));
    }

    public static function deleteAccountAction(): void
    {
        $accountRepo = new AccountRepository(); // account repository

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $accountRepo->deleteAccount($_GET['id']);
            Rendering::renderContent('admin/adminPage');
        }
    }
    
    /**
     * logOut
     *
     * @return void
     */
    public function logOut()
    
    {
        unset($_SESSION['id']);
        // Unset all of the session variables
        $_SESSION = array();
        // Destroy the session.
        session_destroy();
        // Redirect to the homepage
        header('Location: ?');
        exit;
    }
    
}
