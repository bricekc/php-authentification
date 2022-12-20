<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Html\WebPage;

$authentication = new UserAuthentication();

if (!$authentication->isUserConnected()){
    header('location: form.php');
    die();
}

$p = new webPage('affichage du nom');

$p->appendContent(<<<HTML
        <H1>{$authentication->getUserFromSession()->getLastName()} {$authentication->getUserFromSession()->getFirstName()}</H1>
HTML
);

echo $p->toHTML();