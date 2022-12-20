<?php
declare(strict_types=1);

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;
use Html\Helper\Dumper;
use PDO;
use Service\Session;

class User
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $login;
    private string $phone;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public static function findByCredentials(string $login, string $password)
    {
        $requete = MyPdo::getInstance()->prepare(
            <<<SQL
    SELECT id, lastName, firstName, login, phone
    FROM user
    where login= :login and sha512pass=SHA2(:password, 512);
SQL
        );
        $requete->execute([':login'=>$login,':password'=>$password]);
        $requete= $requete->fetchObject(User::class);
        if ($requete==false) {
            throw new EntityNotFoundException("erreur du login ou du mot de passe");
        } else {
            return $requete;
        }
    }
}
