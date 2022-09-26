<?php

use Src\repository\AccountRepository;

$accountRepo = new AccountRepository();
$accounts = $accountRepo->listAccounts();

require 'admin/view/listAccounts.phtml';
