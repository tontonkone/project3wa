<?php
namespace Src\model;

class Credentials {
  private string $_login;
  private string $_password;

  public function setPassword(string $password): string {
      if (!empty($password)) {
          $this->_password = hash('sha256', $password);
          return '';
      }
      return 'Veuillez renseigner votre mot de passe';
  }
  
  public function setLogin(string $login): string {
      if (!empty($login)) {
          $this->_login = $login;
          return '';
      }
      return 'Veuillez renseigner votre login';
  }

  public function isValid(string $accountPassword): bool {
    return $this->_password === $accountPassword;
  }

  public function getLogin(): string {
    return $this->_login;
  }
  public function getPassword(): string {
    return $this->_password;
  }
}
