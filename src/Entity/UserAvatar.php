<?php

declare(strict_types=1);

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;

class UserAvatar
{
    private int $id;
    private ?string $avatar=null;

    public function getId(): int
    {
        return $this->id;
    }
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public static function findById(int $userId): self
    {
        $requete = MyPdo::getInstance()->prepare(
            <<<SQL
    SELECT id, lastname, firstname, login, phone, avatar
    FROM user
    WHERE id={$userId};
SQL
        );
        $requete->execute();
        $requete= $requete->fetchObject(UserAvatar::class);
        if ($requete==false) {
            throw new  EntityNotFoundException("erreur de l'id de l'utilisateur");
        } else {
            return $requete;
        }
    }

    public function setAvatar(?string $avatar): UserAvatar
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function save(): UserAvatar
    {
        $requete = MyPdo::getInstance()->prepare(
            <<<SQL
        UPDATE user 
        SET avatar = :avatar
        WHERE id = :userId
        SQL
        );
        $requete->execute([':avatar'=>$this->getAvatar(),':userId'=>$this->getId()]);
        return $this;
    }

    public function maxFileSize(): int
    {
        return 65535;
    }

    public function isValidFile(string $filename):bool
    {
        if (mime_content_type($filename) === "image/png" && $this->maxFileSize() >= ((getimagesize($filename)['bits'])/8))
        {
            $avatar = UserAvatar::findById($this->getId());
            $avatar->setAvatar(file_get_contents($_FILES['avatar']['tmp_name']));
            $avatar->save();
            $bool =true;
        }
        else
        {
            $bool=false;
        }
        return $bool;
    }
}
