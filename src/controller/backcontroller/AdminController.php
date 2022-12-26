<?php
/**
 * Class fille qui gere les  operations l'admin 
 */
namespace Src\controller\backcontroller;

use Src\core\Secu;
use Src\core\Debug;
use Src\core\Rendering;
use Src\controller\HomeController;

class AdminController extends HomeController {

    public function displayAdminHome()
    {
        $articles = $this->articlesRepository->whereArticlLog();
     /*    Debug::run($articles); */
        $title = "Accueil";
        Rendering::renderContent("admin/adminPage", compact("title","articles"));
    }

    public function createAccount()
    {
        /* formulaire de création */
        $userMessages = [];
        if (isset($_POST['accountSubmit'])) {

            /* verifier si login existe dans la bdd  */
            $user = $this->accountRepository->checkLogin($_POST['login']);
            if ($user) {
                $userMessages['requiredLogin'] = "Ce login existe déjà ";
            } else {
                $userMessages['requiredLogin'] = $this->accountModel->setLogin(Secu::inputValid($_POST['login']));
            }

            /* verifier si email existe dans la bdd */
            $email = $this->accountRepository->checkEmail($_POST['email']);
            if ($email) {
                $userMessages['requiredEmail'] = "Ce email existe déjà";
            } else {
                $userMessages['requiredEmail'] = $this->accountModel->setMail(Secu::inputValid($_POST['email']));
            }
            /**
             * @method static 
             *  Vérifie les champs et mettre à jour les propriétés du compte */

            $userMessages = self::checkAndFillAccount($this->accountModel);

            /* si le formulaire est vide on redirige user sur page formulaire */
            if (empty($userMessages)) {
                $this->accountRepository->createAccount($this->accountModel);
                
                $this->listAccount();
            } else {
                Rendering::renderContent('admin/accountForm', compact('data','userMessages'));
            }
        }

        // Reaffecte les valeurs saisies par l'utilisateur
        // avant qu'il soumettre le formulaire
        $data = $_POST;
        Rendering::renderContent('admin/accountForm', compact('data','userMessages'));
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
    
    /**
     * deleteAccountAction
     *
     * @return void
     */
    public function deleteAccountAction(): void
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $this->accountRepository->deleteAccount($_GET['id']);
            $this->listAccount();
        }
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
                $this->listAccount();
                exit();
            }
        }

        // Converti l'objet Account en tabeau associatif
        // pour l'affichage des valeurs dans le formulaire
        $data = $account->toAssociativeArray();
        /* $accounts = $this->accountRepository->getAccount($_SESSION['id']); */
        Rendering::renderContent('admin/accountForm', compact('data'));
    }

    /**
     * listArticle
     *
     * @return void
     * afficher la page de la liste des articles au visiteur 
     */
    public function listArticle()
    {
        /** récupérer les article depuis la bdd  */
        $articles = $this->articlesRepository->selectAllArticles();
        $titleH1 = "nouveaux blog ";
        $title = "Accueil";
        Rendering::renderContent("admin/listArticle", compact("title", 'titleH1', 'articles'));
    }

}