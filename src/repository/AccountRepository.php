<?php

namespace Src\repository;

use PDO;
use Src\model\{
   Credentials,
   AccountModel};

class AccountRepository extends ManagerRepository {
   
   /**
    * getObject
    *
    * @param  mixed $row
    * @return void
    */
   private function getObject(array $row)
   {
      $account = new AccountModel();
      $account->setId($row['id']);
      $account->setLogin($row['login']);
      $account->setEncryptedPassword($row['password']);
      $account->setFirstName($row['firstName']);
      $account->setLastName($row['lastName']);
      $account->setMail($row['email']);
      $account->setPhoto($row['photo']);
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
      $stmt = $this->_connexion->prepare('SELECT * FROM account WHERE id = :id;');
      $stmt->bindValue('id', $id);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($stmt) {
         return $this->getObject($row);
      }

      return null;
   }


   public function checkLogin($username)
   {
      $stmt = $this->_connexion->prepare("SELECT login FROM account WHERE login = ?");
      $stmt->execute(array($username));
      return $stmt->fetch();
   }
   

   public function checkEmail($mail)
   {
      $stmt = $this->_connexion->prepare("SELECT email FROM account WHERE email = ?");
      $stmt->execute(array($mail));
      return $stmt->fetch();
   }


   /**
    * deleteAccount
    *
    * @param  mixed $id
    * @return void
    */
   public function deleteAccount(string $id): void 
   {
      $stmt = $this->_connexion->prepare(' DELETE FROM account WHERE id = :id;');
      $stmt->bindValue('id', $id);
      $stmt->execute();
   }

   /**
    * createAccount
    *
    * @param  mixed $account
    * @return void
    */
   public function createAccount(AccountModel $account):void 
   {
      $stmt = $this->_connexion->prepare('INSERT INTO account (id, firstName, lastName, email, login, photo, password, isAdmin, created_date)
                                          VALUES (UUID(), :firstName, :lastName,:email, :login, :photo,:password, :isAdmin, NOW() );');
      $stmt->bindValue('firstName', $account->getFirstName());
      $stmt->bindValue('lastName', $account->getLastName());
      $stmt->bindValue('email', $account->getMail());
      $stmt->bindValue('password', $account->getPassword());
      $stmt->bindValue('login', $account->getLogin());
      $stmt->bindValue('photo', $account->getPhoto());
      $stmt->bindValue('isAdmin', $account->isAdmin(), PDO::PARAM_BOOL);

      $stmt->execute();
   }

   /**
    * updateAccount
    *
    * @param  mixed $account
    * @return void
    */
   public function updateAccount(AccountModel $account):void
   {
      $stmt = $this->_connexion->prepare('UPDATE account SET firstName = :firstName, email = :email , lastName  = :lastName, login = :login,password  = :password, isAdmin   = :isAdmin, created_date = :created_date WHERE id = :id;');
      $stmt->bindValue('created_date',$account->getCreatedDate());
      $stmt->bindValue('firstName', $account->getFirstName());
      $stmt->bindValue('lastName', $account->getLastName());
      $stmt->bindValue('login', $account->getLogin());
      $stmt->bindValue('email', $account->getMail());
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
      $stmt = $this->_connexion->prepare('SELECT * FROM account WHERE login = :login');
      $stmt->execute(['login' => $credentials->getLogin()]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$row) {
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
      $stmt = $this->_connexion->prepare('SELECT * FROM account ORDER BY  created_date DESC');
      $stmt->execute();
      $accounts = [];

      foreach ($stmt as $row) {
         $accountId = $row['id'];
         $accounts[$accountId] = $this->getObject($row);
      }
      return $accounts;
   }
}
