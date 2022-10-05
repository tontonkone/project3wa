<?php

namespace Src\repository;

use PDO;
use Src\model\Credentials;
use Src\model\AccountModel;

class AccountRepository extends ManagerRepository
{

   private function getObject(array $row)
   {
      $account = new AccountModel();
      $account->setId($row['id']);
      $account->setLogin($row['login']);
      $account->setEncryptedPassword($row['password']);
      $account->setFirstName($row['firstName']);
      $account->setLastName($row['lastName']);
      $account->setIsAdmin($row['isAdmin']);
      $account->setCreatedDate($row['created_date']);

      return $account;
   }

   /**
    * getAccount
    *
    * @param  mixed $id
    * @return AccountModel
    */

   public function getAccount(string $id): ?AccountModel
   {
      $stmt = $this->_connexion
         ->prepare('SELECT * FROM Account WHERE id = :id;');
      $stmt->bindValue('id', $id);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($stmt) {
         return $this->getObject($row);
      }

      return null;
   }

   /**
    * checkLogin
    *
    * @param  mixed $username
    * @return void
    */

   public function checkLogin($username)
   {
      $stmt = $this->_connexion
         ->prepare("SELECT login FROM Account WHERE login = ?");
      $stmt->execute(array($username));
      return $stmt->fetch();
   }


   /**
    * deleteAccount
    *
    * @param  mixed $id
    * @return void
    */
   public function deleteAccount(string $id)
   {
      $stmt = $this->_connexion
         ->prepare(' DELETE FROM Account WHERE id = :id;');
      $stmt->bindValue('id', $id);
      $stmt->execute();
   }

   /**
    * createAccount
    *
    * @param  mixed $account
    * @return void
    */
   public function createAccount(AccountModel $account)
   {
      $stmt = $this->_connexion
         ->prepare('INSERT INTO Account (id, firstName, lastName, login, password, isAdmin, created_date)
      VALUES (UUID(), :firstName, :lastName, :login, :password, :isAdmin, NOW() );');
      $stmt->bindValue('firstName', $account->getFirstName());
      $stmt->bindValue('lastName', $account->getLastName());
      $stmt->bindValue('login', $account->getLogin());
      $stmt->bindValue('password', $account->getPassword());
      $stmt->bindValue('isAdmin', $account->isAdmin(), PDO::PARAM_BOOL);

      $stmt->execute();
   }

   /**
    * updateAccount
    *
    * @param  mixed $account
    * @return void
    */
   public function updateAccount(AccountModel $account)
   {
      $stmt = $this->_connexion
         ->prepare('UPDATE Account 
         SET firstName = :firstName,
         lastName  = :lastName,
         login = :login, 
         password  = :password, 
         isAdmin   = :isAdmin,
         created_date = :created_date
         WHERE id = :id;');
      $stmt->bindValue('created_date',$account->getCreatedDate());
      $stmt->bindValue('firstName', $account->getFirstName());
      $stmt->bindValue('lastName', $account->getLastName());
      $stmt->bindValue('login', $account->getLogin());
      $stmt->bindValue('password', $account->getPassword());
      $stmt->bindValue('isAdmin', $account->isAdmin(), PDO::PARAM_BOOL);
      $stmt->bindValue('id', $account->getId());

      $stmt->execute();
   }

   /**
    * retrieveAccountFromCredentials
    *
    * @param  mixed $credentials
    * @return AccountModel
    */
   public function retrieveAccountFromCredentials(Credentials $credentials): ?AccountModel
   {
      $stmt = $this->_connexion->prepare('SELECT * FROM Account WHERE login = :login');
      $stmt->execute(['login' => $credentials->getLogin()]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$stmt) {
         return null;
      }

      return $this->getObject($row);
   }

   /**
    * listAccounts
    *
    * @return array
    */
   public function listAccounts(): array
   {
      $stmt = $this->_connexion->prepare('SELECT * FROM Account');
      $stmt->execute();
      $accounts = [];

      foreach ($stmt as $row) {
         $accountId = $row['id'];
         $accounts[$accountId] = $this->getObject($row);
      }
      return $accounts;
   }
}
