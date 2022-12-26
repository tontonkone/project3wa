<?php
namespace Src\model;

class AccountModel {

    private string $id;
    private string $login;
    private string $password;
    private string $password2;
    private string $firstName;
    private string $lastName;
    private bool $isAdmin;
    private string $createdDate;
    private string $mail;
    private string | array $photo;
    
    public function __construct() 
    {
        $this->isAdmin = false;
    }

    public function toAssociativeArray()
    {
        return [
            'id' => $this->getId(),
            'login' => $this->getLogin(),
            'password' => $this->getPassword(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getMail(),
            'photo' => $this->getPhoto(),
            'isAdmin' => $this->isAdmin(),  
        ];
    }

    public function setId($id): string 
    {
        if (isset($this->id)) 
        {
            return 'Un id est déjà spécifié';
        }
        $this->id = $id;
        return '';
    }

    public function setPassword(string $password): string {
        if (strlen($password) >= 6 && strlen($password) <= 20) 
        {
            $this->password = hash('sha256', $password);
            return '';
        }
        return 'Votre mot de passe doit avoir entre 6 et 20 caractères';
    }
    
    public function setEncryptedPassword(string $password): void 
    {
        $this->password = $password;
    }

    public function setLogin(string $login): string 
    {
        if (!empty($login)) {
            $this->login = $login;
            if (! preg_match('/^[a-zA-Z0-9]{5,}$/', $this->login)) 
            { 
                return "Votre login doit être supérieur à 5 lettre et sans caractère spécial";
            }
            return '';
        }
        return 'Veuillez renseigner votre login';
    }
    
    public function setFirstName(string $firstName): string 
    {
        if (!empty($firstName))
        {
            $this->firstName = $firstName;
            return '';
        }
        return 'Veuillez renseigner votre prénom';
    }
    
    public function setLastName(string $lastName): string 
    {
        if (!empty($lastName)) 
        {
            $this->lastName = $lastName;
            return '';
        }
        return 'Veuillez renseigner votre nom de famille';
    }

    public function setIsAdmin(bool $isAdmin): void 
    {
        $this->isAdmin = $isAdmin;
    }
    public function setCreatedDate(string $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
    
    public function getId(): string 
    {
        return $this->id;
    }

    public function getLogin(): string 
    {
        return $this->login;
    }

    public function getPassword(): string 
    {
        return $this->password;
    }

    public function hasPassword(): bool 
    {
        return isset($this->password);
    }
    
    public function getFirstName(): string 
    {
        return $this->firstName;
    }
    
    public function getLastName(): string 
    {
        return $this->lastName;
    }

    public function isAdmin(): bool 
    {
        return $this->isAdmin;
    }

    public function getCreatedDate(): string 
    {
        return $this->createdDate;
    }


    public function setMail($mail)
    {
        if(!empty($mail))
        {
            $this->mail = $mail;
            if (! filter_var($mail, FILTER_VALIDATE_EMAIL)) 
            {
                return  "Votre adresse email n'est pas valide";
            }
            return '';
        }
        return "Veuillez renseigner votre email";
    }

    public function getMail()
    {
        return $this->mail;
    }


    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }


    public function getPhoto()
    {
        return $this->photo;
    }

    public function getPassword2()
    {
        return $this->password2;
    }

    public function setPassword2($password2)
    {
        if(! empty($password2))
        {
            $this->password2 = hash('sha256', $password2);
            return '';
        }
        return 'Veuillez confirmer votre mot de passe';
    }
}
