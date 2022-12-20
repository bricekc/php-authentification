<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Authentication\Exception\AuthenticationException;
use Html\WebPage;
use Html\UserProfileWithAvatar;

$authentication = new UserAuthentication();

$p = new WebPage('Authentification');
$p->appendCssUrl("css/style-test-user.css");
try {
    // Tentative de connexion
    if (!$authentication->isUserConnected()) {
        $user = $authentication->getUserFromAuth();
        $p->appendContent(<<<HTML
<header><h1>Bonjour {$user->getFirstName()}</h1></header>
HTML
        );
    }
    $p->appendContent(<<<HTML
<header><h1>Bonjour {$authentication->getUser()->getFirstName()}</h1></header>
HTML
    );
    $user1Profile = new \Html\UserProfileWithAvatar($authentication->getUser(), $_SERVER['PHP_SELF']);
    $p->appendContent((string)$user1Profile->updateAvatar());
    $p->appendContent($user1Profile->toHTML());
} catch (AuthenticationException $e) {
    // Récupération de l'exception si connexion échouée
    $p->appendContent("Échec d'authentification&nbsp;: {$e->getMessage()}");
} catch (Exception $e) {
    $p->appendContent("Un problème est survenu&nbsp;: {$e->getMessage()}");
}

// Envoi du code HTML au navigateur du client
echo $p->toHTML();
