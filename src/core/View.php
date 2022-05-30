<?php

namespace Core;

use Core\Session;
use Core\Csrf;

class View
{
    /**
     * requires the view with the given view name string
     *
     * @param string $viewName
     * @param array $data
     * @return void
     */
    public static function render(string $viewName, array $data = []): void
    {
        $viewPath = VIEWS_DIR . $viewName . '.view.php';

        // csrf token validated inside Router->runAction();
        $data['csrfToken'] = Csrf::generateToken();

        require $viewPath;  // Have access to $data array

        Session::unset('errors');
        Session::unset('message');
        Session::unset('verificationFailed');
        exit();
    }

    /**
     * Throw 404 error.
     *
     * @return void
     */
    public static function throw404(): void
    {
        http_response_code(404);
        self::render('404', [
            'pageTitle' => '404 - Page Not Found'
        ]);
    }

    /**
     * Throw 403 error.
     *
     * @return void
     */
    public static function throw403(): void
    {
        http_response_code(403);
        self::render('error', [
            'pageTitle' => '401 - Error'
        ]);
    }
}