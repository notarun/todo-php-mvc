<?php

namespace Core;

use Core\View;
use Core\Session;

class Csrf
{
    public static function generateToken(): string
    {
        if (!Session::has('csrf-token')) {
            Session::set('csrf-token', bin2hex(random_bytes(32)));
        }

        return Session::get('csrf-token');
    }

    public static function validateToken()
    {
        if (!isset($_REQUEST['csrf-token'], $_SESSION['csrf-token'])) {
            View::render('error', [
                'pageTitle' => 'Something went wrong',
            ]);
        }

        if (!hash_equals($_SESSION['csrf-token'], $_REQUEST['csrf-token'])) {
            Session::unset('csrf-token');
            return false;
        }

        return true;
    }
}