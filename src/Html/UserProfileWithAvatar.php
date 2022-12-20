<?php

namespace Html;

use Entity\User;
use Entity\UserAvatar;
use Html\Helper\Dumper;
use JetBrains\PhpStorm\Pure;

class UserProfileWithAvatar extends UserProfile
{
    public const AVATAR_INPUT_NAME ='avatar';
    private string $formAction;

    public function __construct(User $user, string $formAction)
    {
        parent::__construct($user);
        $this->formAction=$formAction;
    }

    public function toHtml(): string
    {
        $avatar=self::AVATAR_INPUT_NAME;
        $html=UserProfile::toHtml();
        $user=$this->getUser();
        $img=UserAvatar::findById($user->getId());
        $html.=<<<HTML
<form method="post" action="{$this->formAction}" enctype='multipart/form-data'>
    <input type="file" name="{$avatar}">
    <input type="submit" value="ok">
</form>
<img src="avatar.php?userId={$user->getId()}">
</main>
HTML;
        return $html;
    }

    public function updateAvatar():bool
    {
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0
            && $_FILES['avatar']['size'] !== 0 && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            $avatar = UserAvatar::findById($this->getUser()->getId());
            $avatar->isValidFile($_FILES['avatar']['tmp_name']);
            return true;
        }
        else {
            return false;
        }

    }
}
