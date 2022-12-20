<?php

namespace Service;

use Service\Exception;
use Service\Exception\SessionException;

class Session
{
    public static function start(): void
    {
        $statut = session_status();
        if ($statut === PHP_SESSION_DISABLED) {
            throw new SessionException();
        } elseif ($statut === PHP_SESSION_NONE) {
            if (headers_sent()) {
                throw new SessionException();
            } else {
                session_start();
            }
        }
    }
}
