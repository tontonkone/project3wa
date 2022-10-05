<?php
namespace Src\model;

class AccountModel {
    private string $_id;
    private string $_login;
    private string $_password;
    private string $_firstName;
    private string $_lastName;
    private bool $_isAdmin;
    private string $_createdDate;
    
    public function __construct() {
        $this->_isAdmin = false;
    }

    public function toAssociativeArray() {
        return [
            'id' => $this->getId(),
            'login' => $this->getLogin(),
            'password' => $this->getPassword(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'isAdmin' => $this->isAdmin(),
        ];
    }

    public function setId($id): string {
        if (isset($this->_id)) {
        return 'Un id est déjà spécifié';
        }
        $this->_id = $id;
        return '';
    }

    public function setPassword(string $password): string {
        if (strlen($password) >= 1 && strlen($password) <= 20) { // a changer
            $this->_password = hash('sha256', $password);
            return '';
        }
        return 'Votre mot de passe doit avoir entre 12 et 20 caractères';
    }
    
    public function setEncryptedPassword(string $password): void {
        $this->_password = $password;
    }

    public function setLogin(string $login): string {
        if (!empty($login)) {
            $this->_login = $login;
            return '';
        }
        return 'Veuillez renseigner votre login';
    }
    
    public function setFirstName(string $firstName): string {
        if (!empty($firstName)) {
            $this->_firstName = $firstName;
            return '';
        }
        return 'Veuillez renseigner votre prénom';
    }
    
    public function setLastName(string $lastName): string {
        if (!empty($lastName)) {
            $this->_lastName = $lastName;
            return '';
        }
        return 'Veuillez renseigner votre nom de famille';
    }

    public function setIsAdmin(bool $isAdmin): void {
        $this->_isAdmin = $isAdmin;
    }
    public function setCreatedDate(string $createdDate): void
    {
        $this->_createdDate = $createdDate;
    }
    
    public function getId(): string {
        return $this->_id;
    }

    public function getLogin(): string {
        return $this->_login;
    }

    public function getPassword(): string {
        return $this->_password;
    }

    public function hasPassword(): bool {
        return isset($this->_password);
    }
    
    public function getFirstName(): string {
        return $this->_firstName;
    }
    
    public function getLastName(): string {
        return $this->_lastName;
    }

    public function isAdmin(): bool {
        return $this->_isAdmin;
    }

    public function getCreatedDate(): string 
    {
        return $this->_createdDate;
    }
}
