<?php

namespace App\core;

class SessionManager
{
    public function __construct()
    {
        session_start();

        if (isset($_SESSION['destroyed']) && $_SESSION['destroyed'] <= time() - 180) {
            session_regenerate_id(true);
        } else if (!isset($_SESSION['destroyed'])) {
            $_SESSION['destroyed'] = time() + 180;
        }
    }
}