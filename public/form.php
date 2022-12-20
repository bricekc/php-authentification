<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Html\WebPage;

// Création de l'authentification
$authentication = new UserAuthentication();

$p = new WebPage('Authentication');
//vérification si un utilisateur est connecté
if ($authentication->isUserConnected()) {
    $logout=$authentication->logoutForm('form.php', 'déconnexion');
    $p->appendContent(
        <<<HTML
        {$logout}
HTML
    );
    $authentication->logoutIfRequested();
} else {
    // Production du formulaire de connexion
    $p->appendCSS(
        <<<CSS
        form input {
            width : 4em ;
        }
    CSS
    );
    $form = $authentication->loginForm('auth.php');
    $p->appendContent(
        <<<HTML
        {$form}
        <p>Pour faire un test : essai/toto
    HTML
    );
}
echo $p->toHTML();
