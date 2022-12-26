<?php
/**
 * @class fille extends de HomeController 
 * utilisé pour controller les connexion a la page admin / user
 * et pour l'inscription et la protection contre les injections
 */
namespace Src\controller\frontController;

use Src\controller\backcontroller\AdminController;
use Src\controller\HomeController;
use Src\core\Secu;

use Src\core\Debug;

use Src\core\Rendering;
use Src\model\Credentials;

class AuthController extends HomeController {

    public function __construct()
    {
        parent::__construct();
    }

    public function register()
    {
        $userMessages = [
            'sendSuccess' => '',
            'requiredFirstName' => '',
            'requiredLastName' => '',
            'requiredLogin' => '',
            'requiredPassword' => '',
            'requiredPassword2' => '',
            'loginFail' => '',
            'requiredImage' => '',
            'requiredEmail' => '',
        ];

        /* User action  sur le bouton "Créer" du formulaire de création d'un compte */
        if (isset($_POST['createAccountSubmit'])) 
        {
            $userName = $_POST['formLogin'];
            $pass1 = $this->accountModel->setPassword(Secu::inputValid($_POST['formPassword']));
            $pass2 = $this->accountModel->setPassword2(Secu::inputValid($_POST['formPassword2']));

            $userMessages['requiredFirstName'] = $this->accountModel->setFirstName(Secu::inputValid($_POST['formFirstName']));
            $userMessages['requiredLastName'] = $this->accountModel->setLastName(Secu::inputValid($_POST['formLastName']));

            /* verifier si login existe dans la bdd  */
            $user = $this->accountRepository->checkLogin($_POST['formLogin']);
            if ($user)
            {
                $userMessages['requiredLogin'] = "Ce login existe déjà ";
            }
            else 
            {
                $userMessages['requiredLogin'] = $this->accountModel->setLogin(Secu::inputValid($_POST['formLogin']));
            }
            
            /* verifier si email existe dans la bdd */
            $email = $this->accountRepository->checkEmail($_POST['formEmail']);
            if($email)
            {
               $userMessages['requiredEmail'] = "Ce email existe déjà";
            }
            else
            {
                $userMessages['requiredEmail'] = $this->accountModel->setMail(Secu::inputValid($_POST['formEmail']));
            }
            /*  verifier si les mots de passes sont identiques  */
            
            $userMessages['requiredPassword'] = $this->accountModel->setPassword(Secu::inputValid($_POST['formPassword']));
            $userMessages['requiredPassword2'] = $this->accountModel->setPassword2(Secu::inputValid($_POST['formPassword2']));

            if($pass1 !== $pass2)
            {
                $userMessages['requiredPassword2'] = "Vos mots de passes ne sont pas identique";
            }
            $imageName = $_FILES['image']['name']; // le nom du fichier 
            $imageTmp = $_FILES['image']['tmp_name']; // le nom temporaire du fichier
            $imageSize = $_FILES['image']['size']; // la taille de l'image

            $imageExplode = explode('.', $imageName);
            $newNameImage = uniqid($userName) . '.' . end($imageExplode);
            $newNameImagePath = '../public/assets/img/pic_profils/' . $newNameImage;
            $imageExtension = strrchr($newNameImage, ".");
            $extensionArray = array('.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
            $sizeMax = 2000000;

            /* Si tous les champs obligatoires sont remplis, on crée le compte */
            if (empty($userMessages['requiredFirstName']) && 
                empty($userMessages['requiredLastName']) &&
                empty($userMessages['requiredLogin']) && 
                empty($userMessages['requiredPassword']) &&
                empty($userMessages['requirePassword2'])&&
                empty($userMessages['requiredMail']))
            {
                if (! empty($_FILES)) 
                {
                    if ($imageSize > $sizeMax) {
                        $userMessages['requiredImage'] = "La taille de votre image ne peut pas depasser 2Mo";
                    }
                    if (!in_array($imageExtension, $extensionArray) && $imageSize !== 0) {
                        $userMessages['requiredImage'] = "Vous ne pouvez télécharger que des fichiers jpg, jpeg ou png ";
                    }
                }
                if($imageSize === 0)
                {
                    $newNameImagePath ="";
                }
                $this->accountModel->setPhoto($newNameImagePath);
                $this->accountRepository->createAccount($this->accountModel);
                
                 move_uploaded_file($imageTmp, $newNameImagePath);

                $userMessages['sendSuccess'] = 'Compte crée avec success';
                $title = "Inscription";
                Rendering::renderContent('register', compact('userMessages', 'title'));
                exit();

            }
        }
        $title = "Inscription";
        Rendering::renderContent('register', compact('userMessages', 'title'));
    }
        /**
         * connexion 
         */
        
        /* user a envoyé ses identifiants */
    public function connexion()
    {
        $userMessages = [
            'requiredLogin' => '',
            'requiredPassword' => '',
            'loginFail' => '',
        ];
        if (isset($_POST['loginSubmit']))
        {
            $credentials = new Credentials();
            $userMessages['requiredLogin'] = $credentials->setLogin(Secu::inputValid($_POST['formLogin']));
            $userMessages['requiredPassword'] = $credentials->setPassword($_POST['formPassword']);
            /*  Si tous les champs obligatoires sont remplis */
            if (empty($userMessages['requiredLogin']) && empty($userMessages['requiredPassword'])) 
            {
                $account = $this->accountRepository->retrieveAccountFromCredentials($credentials);

                if ($account) 
                {
                    if ($credentials->isValid($account->getPassword())) 
                    {
                        /**
                         * connexion éffectuée
                         * on affecte aux sessions les infos du compte user 
                         */
                        $_SESSION['isLogged'] = true;


                        $_SESSION['isLogged'] = true;
                        $_SESSION['id'] = $account->getId();
                        $_SESSION['firstName'] = $account->getFirstName();
                        $_SESSION['lastName'] = $account->getLastName();
                        $_SESSION['photo'] = $account->getPhoto();
                        
                        
                        
                        
                        // On stocke les informations de l'utilisateur dans des cookies pendant 30 jours
                        setcookie('firstName', $account->getFirstName(), time() + 3600 * 24 * 30, '/');
                        setcookie('lastName', $account->getLastName(), time() + 3600 * 24 * 30, '/');

                        /* si admin redirection page admin */
                        if ($account->isAdmin())
                        {
                            $_SESSION['isAdmin'] = true;
                            
                            header('Location:?page=admin');
                        } 
                        else 
                        {
                        /* sinon user simple redirection  page home */
                        
                            header('Location:?page=user');
                        }
                    }
                }
                $userMessages['loginFail'] = 'Le couple login/mot de passe est inconnu';
            }
        }
        $title="Connexion";
        Rendering::renderContent('connexion', compact('userMessages','title'));
    }

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