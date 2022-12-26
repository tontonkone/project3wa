<?php
/**
 * @Class abstract
 * Le controleur principal 
 * utilisé pour les methodes, les propriétés en commun aux class filles 
 * et pour instancier les class utilisés dans les filles 
 * 
 */

namespace Src\controller;

use Src\model\CommentModel;

use Src\repository\CommentRepository;

use Src\core\Debug;



use Src\core\Rendering;
use Src\model\{
    AccountModel,
    ArticleModel};
use Src\repository\{
    AccountRepository,
    ArticleRepository};

 abstract class HomeController {

    public function __construct()
    {
        $this->articleRepository = new ArticleRepository();
        $this->articleModel = new ArticleModel();

        $this->commentModel = new CommentModel();
        $this->commentRepository = new CommentRepository();
        
        $this->articlesRepository = new ArticleRepository();
        $this->articleModel = new ArticleModel();

        $this->accountModel = new AccountModel();
        $this->accountRepository = new AccountRepository();  

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
        if (!empty($_GET['id'])) 
        {
            return $accountRepository->getAccount(htmlentities($_GET['id']));
        }

        return null;
    }
    /**
     * Vérifie les champs du formulaire et mettre  à jour les propriétés du compte
     * 
     * @param Account $account - Compte à créer / mettre à jour
     * 
     * @return array Liste des erreurs s'il y en a (tableau vide non)
     * 
     */

    static public function checkAndFillAccount(AccountModel $accountModel): array
    {
        $errors = [
            'requiredFirstName' => '',
            'requiredLastName' => '',
            'requiredLogin' => '',
            'requiredPassword' => '',
            'requiredPhoto' => ''

        ];

        $errors['requiredFirstName'] = $accountModel->setFirstName(htmlentities($_POST['firstName']));
        $errors['requiredLastName'] = $accountModel->setLastName(htmlentities($_POST['lastName']));
        $errors['requiredLogin'] = $accountModel->setLogin(htmlentities($_POST['login']));
        $errors['requiredEmail'] = $accountModel->setMail(htmlentities($_POST['email']));
        @$accountModel->setPhoto(htmlentities($_POST['photo']));

        if (!(empty($_POST['password']) && $accountModel->hasPassword())) 
        {
            $errors['requiredPassword'] = $accountModel->setPassword($_POST['password']);
        }

        if (isset($_POST['isAdmin'])) 
        {
            $accountModel->setIsAdmin(true);
        } 
        else 
        {
            $accountModel->setIsAdmin(false);
        }

        /**
         * array_filter :
         * DEF: Filtre les éléments d'un tableau grâce à une fonction de rappel
         * on lui donne  un tableaux a parcourir errors il return la valeur courante
         * si le resultat n'est pas vide
         */ 
        $errors = array_filter($errors, function ($v) { return !empty($v);});

        return $errors;
    }
    
}
