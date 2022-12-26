<?php
namespace Src\model;

class Credentials {
  private string $login;
  private string $password;

  public function setPassword(string $password): string {
      if (!empty($password)) {
          $this->password = hash('sha256', $password);
          return '';
      }
      return 'Veuillez renseigner votre mot de passe';
  }
  
  public function setLogin(string $login): string {
      if (!empty($login)) {
          $this->login = $login;
          return '';
      }
      return 'Veuillez renseigner votre login';
  }

  public function isValid(string $accountPassword): bool {
    return $this->password === $accountPassword;
  }

  public function getLogin(): string {
    return $this->login;
  }
  public function getPassword(): string {
    return $this->password;
  }
}
