<?php 
namespace Src\controller;


use Src\core\Rendering;
use Src\model\AccountModel;
use Src\repository\AccountRepository;
use Src\repository\ArticleRepository;

class HomeController{

    public function __construct()
    {
        $this->articlesRepository = new ArticleRepository();
    }

    /**
     * *****************DISPLAY_HOME ****************************
     * **********************************************************
     */
    public function displayHome(){
        $articles = $this->articlesRepository->selectAllElements();
        $titleH1 = "nouveaux blog ";
        $title = "Accueil";
        Rendering::renderContent("homePage", compact("title", 'titleH1','articles'));
    }

    /**
     * *****************LIST_ARTICLES ****************************
     * ***********************************************************
     *
     */
    
    public function listArticle()
    {
        $articles = $this->articlesRepository->selectAllElements();
        $titleH1 = "nouveaux blog ";
        $title = "Accueil";
        Rendering::renderContent("articles/listArticle", compact("title", 'titleH1','articles'));
    }


    static public function retrieveAccountFromRequest(AccountRepository $accountRepository): ?AccountModel
    {
        if (!empty($_GET['id'])) {
            return $accountRepository->getAccount( htmlentities($_GET['id']));
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
    static public function checkAndFillAccount(AccountModel $account): array
    {
        $errors = [
            'requiredFirstName' => '',
            'requiredLastName' => '',
            'requiredLogin' => '',
            'requiredPassword' => '',
        ];

        $errors['requiredFirstName'] = $account->setFirstName(
            htmlentities($_POST['firstName'])
        );
        $errors['requiredLastName'] = $account->setLastName(
            htmlentities($_POST['lastName'])
        );
        $errors['requiredLogin'] = $account->setLogin(
            htmlentities($_POST['login'])
        );
        if (!(empty($_POST['password']) && $account->hasPassword())) {
            $errors['requiredPassword'] = $account->setPassword(
                $_POST['password']
            );
        }

        if (isset($_POST['isAdmin'])) {
            $account->setIsAdmin(true);
        } else {
            $account->setIsAdmin(false);
        }

        // Filter errors array : keep only not empty values
        $errors = array_filter($errors, function ($v) {
            return !empty($v);
        });

        return $errors;
    }
/**
 * ***************** ADMIN EDIT_ACCOUNT *********************
 * **********************************************************
 */
    public function editAccount()
    {
        $accountRepository = new AccountRepository(); // account repository
        // Récupère le compte depuis la BDD
        $account = self::retrieveAccountFromRequest($accountRepository);

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
                $accountRepository->updateAccount($account);
                header('Location:?');
            }
        }

        // Converti l'objet Account en tabeau associatif
        // pour l'affichage des valeurs dans le formulaire
        $data = $account->toAssociativeArray(); 
        Rendering::renderContent('admin/accountForm', compact('data'));
    }

    /**
     * *****************ADMIN CREATE_ACCOUNT ********************
     * **********************************************************
     */
    public function createAccount() 
    {
        $accountRepository = new AccountRepository(); //account repository
        $account = new AccountModel(); // accountmodel 
        // Créé un nouveau compte
        // Processus de soumission du formulaire de création
        $userMessages = [];
        if (isset($_POST['accountSubmit'])) {
            // Vérifie les champs et met à jour les propriétés du compte
            $userMessages = self::checkAndFillAccount($account);

            // Si le formulaire est valide, l'enregistre en BDD
            // et redirige l'utilisateur vers la page d'accueil
            if (empty($userMessages)) {
                $accountRepository->createAccount($account);
                header('Location:?');
            }
        }

    // Reaffecte les valeurs saisies par l'utilisateur
    // avant qu'il soumettre le formulaire
        $data = $_POST;
        Rendering::renderContent('admin/accountForm', compact('data'));
    }

    /**
     * ***************** ADMIN_LIST_ACCOUNT *********************
     * **********************************************************
     */
    public function listAccount()
    {

        $accountRepo = new AccountRepository(); // accont repository
        $accounts = $accountRepo->listAccounts();
        $titleH1 = "Liste des Utilisateurs";
        $title = "Utilisateur";
        Rendering::renderContent('admin/listAccounts', compact('title', 'titleH1','accounts')) ;
    }

    /**
     * ***************** ADMIN_DELETE_ACCOUNT*********************
     * **********************************************************
     */
    public static function deleteAccountAction(): void 
    {
        $accountRepo = new AccountRepository(); // account repository

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $accountRepo->deleteAccount($_GET['id']);
            Rendering::renderContent('admin/adminPage');
        }
    }
}