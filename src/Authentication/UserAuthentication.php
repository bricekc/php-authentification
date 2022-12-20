<?php

namespace Authentication;

use Authentication\Exception\AuthenticationException;
use Authentication\Exception\NotLoggedInException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Html\StringEscaper;
use Service\Session;

class UserAuthentication
{
    private ?User $user=null;
    private const LOGIN_INPUT_NAME = 'login';
    private const PASSWORD_INPUT_NAME = 'password';
    private const SESSION_KEY = '__UserAuthentication__';
    private const SESSION_USER_KEY = 'user';
    private const LOGOUT_INPUT_NAME = 'logout';

    public function __construct()
    {
        try{
        $this->user = self::getUserFromSession();} catch (\Exception){

        }
    }

    public function loginForm(string $action, string $submitText='OK')
    {
        $login=self::LOGIN_INPUT_NAME;
        $password=self::PASSWORD_INPUT_NAME;
        return <<<HTML
<form action="{$action}" method="post">
    <input type="text" placeholder="login" name="{$login}">
    <input type="text" placeholder="pass" name="{$password}">
    <input type="submit" value="{$submitText}">
</form>
HTML;
    }

    public function getUserFromAuth()
    {
        $login=self::LOGIN_INPUT_NAME;
        $password=self::PASSWORD_INPUT_NAME;
        $user = user::findByCredentials($_POST[$login], $_POST[$password]);
        if (!empty($user)) {
            $this->setUser($user);
            return $user;
        } else {
            return AuthenticationException::class;
        }
    }

    public function setUser(?User $user): void
    {
        Session::start();
        $this->user = $user;
        $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]=$this->user;
    }

    public function isUserConnected()
    {
        Session::start();
        $bool=false;
        if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]) and $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]instanceof User) {
            $bool=true;
        }
        return $bool;
    }

    public function logoutForm(string $action, string $text)
    {
        $logout = self::LOGOUT_INPUT_NAME;
        return <<<HTML
<form action="{$action}" method="post">
    <input type="submit" name="{$logout}"value="{$text}">
</form>
HTML;
    }

    public function logoutIfRequested()
    {
        if (isset($_POST[self::LOGOUT_INPUT_NAME])) {
            unset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]);
            $this->setUser(null);
        }
    }

    public function getUserFromSession(): User
    {
        Session::start();
        if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]) and $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]instanceof User) {
            return $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY];
        } else {
            throw new NotLoggedInException("pas d'utilisateur trouvé dans la session");
        }
    }

    public function getUser()
    {
        if (isset($this->user)) {
            return $this->user;
        } else {
            throw new NotLoggedInException("pas d'utilisateur trouvé");
        }
    }
}
