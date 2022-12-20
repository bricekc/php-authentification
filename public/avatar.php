<?php

try {
    if (!isset($_GET['userId'])) {
        throw new \Entity\Exception\EntityNotFoundException("id de l'utilisateur introuvable");
    }
    $userId = $_GET['userId'];
    $avatar = \Entity\UserAvatar::findById((int)$userId);
    if ($avatar->getAvatar()==null) {
        $default = file_get_contents('img/default_avatar.png');
        header('Content-Type: image/png');
        echo $default;
    } else {
        header('Content-Type: image/png');
        echo $avatar->getAvatar();
    }
} catch (\Entity\Exception\EntityNotFoundException) {
    $default = file_get_contents('img/default_avatar.png');
    header('Content-Type: image/png');
    echo $default;
}
