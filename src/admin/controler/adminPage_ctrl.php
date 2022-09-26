<?php

use Src\repository\AccountRepository;

/**
 * Delete Account Process
 */
function deleteAccountAction() {
  $accountRepo = new AccountRepository();
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $accountRepo->deleteAccount($_GET['id']);
  }
}

// Gestionnaire des actions à réaliser sur la classe Account

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
  case 'createAccount':
  case 'editAccount':
      require 'admin/controler/accountForm_ctrl.php';
      break;
  case 'deleteAccount':
    deleteAccountAction();
    require 'admin/view/adminPage.phtml';
    break;
  case 'listAccounts':
    require 'admin/controler/listAccounts_ctrl.php';
    break;
  default:
    require 'admin/view/adminPage.phtml';
}
