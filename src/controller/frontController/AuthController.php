<?php
namespace Src\controller\frontController;

use Src\controller\HomeController;
use Src\core\Rendering;
use Src\model\Credentials;
use Src\model\AccountModel;
use Src\repository\AccountRepository;

class AuthController extends HomeController {

    public function authVerif()
    {

        $accountRepository = new AccountRepository();

        $userMessages = [
            'sendSuccess' => '',
            'requiredFirstName' => '',
            'requiredLastName' => '',
            'requiredLogin' => '',
            'requiredPassword' => '',
            'loginFail' => '',
        ];

        // L'utilisateur a cliqué sur le bouton "Créer" du formulaire de création d'un compte
        if (isset($_POST['createAccountSubmit'])) {
            $account = new AccountModel();
            $userMessages['requiredFirstName'] = $account->setFirstName(
                htmlentities($_POST['formFirstName'])
            );
            $userMessages['requiredLastName'] = $account->setLastName(
                htmlentities($_POST['formLastName'])
            );
            $user = $this->accountRepository->checkLogin($_POST['formLogin']);
            if ($user){
                $userMessages['requiredLogin'] = "ce login déjà est prit ";
            } else {
                $userMessages['requiredLogin'] = $account->setLogin(
                    htmlentities($_POST['formLogin'])
                );
            }

            $userMessages['requiredPassword'] = $account->setPassword(
                $_POST['formPassword']
            );
            // Si tous les champs obligatoires sont remplis, on crée le compte
            if (
                empty($userMessages['requiredFirstName']) && empty($userMessages['requiredLastName']) &&
                empty($userMessages['requiredLogin']) && empty($userMessages['requiredPassword'])
            ) {
                
                $accountRepository->createAccount($account);
                $userMessages['sendSuccess'] = 'Votre compte a bien été créé';
            }
        }

        // L'utilisateur a envoyé ses identifiants
        if (isset($_POST['loginSubmit'])) {
            $credentials = new Credentials();
            $userMessages['requiredLogin'] = $credentials->setLogin( htmlentities($_POST['formLogin'])
            );
            $userMessages['requiredPassword'] = $credentials->setPassword(
                $_POST['formPassword']
            );

            // Si tous les champs obligatoires sont remplis
            if (empty($userMessages['requiredLogin']) && empty($userMessages['requiredPassword'])) {
                $account = $accountRepository->retrieveAccountFromCredentials($credentials);

                if ($account) {
                    if ($credentials->isValid($account->getPassword())) {
                        // Connexion réussie
                        $_SESSION['isLogged'] = true;
                        $_SESSION['id'] = $account->getId();
                        $_SESSION['firstName'] = $account->getFirstName();
                        $_SESSION['lastName'] = $account->getLastName();
                        
                        
                        // On stocke les informations de l'utilisateur dans des cookies pendant 30jours
                        setcookie('firstName', $account->getFirstName(), time() + 3600 * 24 * 30, '/');
                        setcookie('lastName', $account->getLastName(), time() + 3600 * 24 * 30, '/');

                        if ($account->isAdmin()) {
                            $_SESSION['isAdmin'] = true;

                            // Si l'utilisateur est un admin, on le redirige vers la page d'admin
                            header('Location:?page=admin');
                        } else {
                            // Sinon, on redirige l'utilisateur vers la page d'accueil
                            header('Location:?page=home');
                        }
                    }
                }
                $userMessages['loginFail'] = 'Le couple login/mot de passe est inconnu';
            }
        }
        $title="Accueil";
        Rendering::renderContent('loginPage', compact('userMessages','title'));
    }

}
?>