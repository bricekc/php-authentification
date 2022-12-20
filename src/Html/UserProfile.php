<?php

namespace Html;
use Entity\User;
class UserProfile
{
    use StringEscaper;
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function getUser()
    {
        return $this->user;
    }
    public function toHtml()
    {
        return <<<HTML
<main>
<div class="content">
<div>Nom :</div>
<nav>{$this->escapeString($this->user->getLastName())}</nav>
<div>Prénom :</div>
<nav>{$this->escapeString($this->user->getFirstName())}</nav>
<div>Login :</div>
<nav>{$this->escapeString($this->user->getLogin())} [{$this->escapeString($this->user->getId())}]</nav>
<div>Téléphone :</div>
<nav>{$this->escapeString($this->user->getPhone())}</nav>
</div>
HTML;
    }
}