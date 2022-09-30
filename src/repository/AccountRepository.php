<?php
namespace Src\repository;
use PDO;
use Src\model\Credentials;
use Src\model\AccountModel;

class AccountRepository {


  private PDO $_connexion;

  public function __construct()
  {
    $this->_connexion = DataBase::getConnexion();
  }
  public function getAccount(string $id): ?AccountModel {
    $stmt = $this->_connexion->prepare('
        SELECT * FROM Account WHERE id = :id;
    ');
    $stmt->bindValue('id', $id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $account = new AccountModel();
      $account->setId($row['id']);
      $account->setLogin($row['login']);
      $account->setEncryptedPassword($row['password']);
      $account->setFirstName($row['firstName']);
      $account->setLastName($row['lastName']);
      $account->setIsAdmin($row['isAdmin']);

      return $account;
    }

    return null;
  }

  public function deleteAccount(string $id) {
    $stmt = $this->_connexion->prepare('
        DELETE FROM Account WHERE id = :id;
    ');
    $stmt->bindValue('id', $id);
    $stmt->execute();
  }

  public function createAccount(AccountModel $account) {
    $stmt = $this->_connexion->prepare('INSERT INTO Account (id, firstName, lastName, login, password, isAdmin)
                                        VALUES (UUID(), :firstName, :lastName, :login, :password, :isAdmin);
    ');

    $stmt->bindValue('firstName', $account->getFirstName());
    $stmt->bindValue('lastName', $account->getLastName());
    $stmt->bindValue('login', $account->getLogin());
    $stmt->bindValue('password', $account->getPassword());
    $stmt->bindValue('isAdmin', $account->isAdmin(), PDO::PARAM_BOOL);

    $stmt->execute();
  }

  public function updateAccount(AccountModel $account) {
    $stmt = $this->_connexion->prepare('UPDATE Account
          SET firstName = :firstName,
              lastName  = :lastName,
              login     = :login,
              password  = :password,
              isAdmin   = :isAdmin
        WHERE id = :id;
    ');

    $stmt->bindValue('firstName', $account->getFirstName());
    $stmt->bindValue('lastName', $account->getLastName());
    $stmt->bindValue('login', $account->getLogin());
    $stmt->bindValue('password', $account->getPassword());
    $stmt->bindValue('isAdmin', $account->isAdmin(), PDO::PARAM_BOOL);
    $stmt->bindValue('id', $account->getId());

    $stmt->execute();
  }

  public function retrieveAccountFromCredentials(Credentials $credentials): ?AccountModel {
    $stmt = $this->_connexion->prepare('SELECT * FROM Account WHERE login = :login');
    $stmt->execute([
        'login' => $credentials->getLogin()
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
      return null;
    }

    $account = new AccountModel();
    $account->setLogin($result['login']);
    $account->setEncryptedPassword($result['password']);
    $account->setFirstName($result['firstName']);
    $account->setLastName($result['lastName']);
    $account->setIsAdmin($result['isAdmin']);

    return $account;
  }

  public function listAccounts(): array {
    $stmt = $this->_connexion->prepare('
      SELECT * FROM Account
    ');
    $stmt->execute();

    $accounts = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $account = new AccountModel();
      $account->setId($row['id']);
      $account->setLogin($row['login']);
      $account->setEncryptedPassword($row['password']);
      $account->setFirstName($row['firstName']);
      $account->setLastName($row['lastName']);
      $account->setIsAdmin($row['isAdmin']);

      array_push($accounts, $account);
    }

    return $accounts;
  }
}
