<?php

// Router entre les pages de l'application

// Si l'utilisateur est loggué
if (isset($_SESSION['isLogged'])) {
    $page = isset($_GET['page']) ? $_GET['page'] : '';

    // Si l'utilisateur est un admin
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) {
        // Router entre les pages de la partie admin (Backoffice)
        switch($page) {
            case 'admin':
            default:
                require 'src/admin/controler/adminPage_ctrl.php';
                break;
        }
    
    } else {
        // Router entre les pages de la partie utilisateur non admin (Frontoffice)
        switch($page) {
            case 'home':
            default:
                require 'src/controler/home_ctrl.php';
                break;
        }    
    }

    exit();
}

// Formulaires de login et d'inscription
require 'src/controler/login_ctrl.php';

?>
