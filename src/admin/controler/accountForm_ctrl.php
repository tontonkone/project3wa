<?php

use Src\model\Account;
use Src\repository\AccountRepository;

/*************************************************************************
 *                Account Form create / edit Controler                   *
 *************************************************************************/

/**
 * Tente de récupérer l'Account dans la BDD depuis l'id fourni par la requête
 * 
 * @param AccountRepository $accountRepository
 * 
 * @return Account | null Retourne le compte s'il l'a trouvé 
 */
function retrieveAccountFromRequest(AccountRepository $accountRepository): ?Account {
    if (!empty($_GET['id'])) {
        return $accountRepository->getAccount(
            htmlentities($_GET['id'])
        );
    }

    return null;
}

/**
 * Vérifie les champs du formulaire et met à jour les propriétés du compte
 * 
 * @param Account $account - Compte à créer / mettre à jour
 * 
 * @return array Liste des erreurs s'il y en a (tableau vide non)
 */
function checkAndFillAccount(Account $account): array {
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
    $errors = array_filter($errors, function ($v) { return !empty($v); });

    return $errors;
}

/**
 * Edit Account Process
 */
function editAccount() {
    $accountRepository = new AccountRepository();
    // Récupère le compte depuis la BDD
    $account = retrieveAccountFromRequest($accountRepository);

    // S'il ne l'a pas trouvé, redirige l'utilisateur vers la page d'accueil
    if(empty($account)) {
        header('Location:?');
        exit();
    }

    // Processus de soumission du formulaire d'édition
    $userMessages = [];
    if (isset($_POST['accountSubmit'])) {
        // Vérifie les champs et met à jour les propriétés du compte
        $userMessages = checkAndFillAccount($account);

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
    require 'admin/view/accountForm.phtml';
}

/**
 * Create Account Process
 */
function createAccount() {
    $accountRepository = new AccountRepository();
    // Créé un nouveau compte
    $account = new Account();

    // Processus de soumission du formulaire de création
    $userMessages = [];
    if (isset($_POST['accountSubmit'])) {
        // Vérifie les champs et met à jour les propriétés du compte
        $userMessages = checkAndFillAccount($account);

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
    require 'admin/view/accountForm.phtml';
}

switch($_GET['action']) {
    case 'editAccount':
        editAccount();
        break;
    case 'createAccount':
        createAccount();
        break;
    default:
    header('Location:?');
}
