<?php

namespace App\Middlewares;

use Core\View;
use Core\Session;

/**
 * Protects the page from non logged in users.
 */
trait Auth
{
    /**
     * Authentication for loggedin and not-loggedin users.
     *
     * @return void
     */
    public function auth($params = [])
    {
        $currentUri = $_SERVER['REQUEST_URI'];

        if (!$this->user->isLoggedin()) {
            if (!$this->isRestrictedRoute($currentUri)) {
                $this->redirect('/login');
            }

            if ($currentUri === '/verify') {
                $this->redirect('/login');
            }
        } else {
            if ($this->isRestrictedRoute($currentUri)) {
                $this->redirect('/');
            }
        }
    }

    /**
     * Whether the logged in user have permission
     * to do action on the item or not.
     * Also throws 404 if item doesn't exists.
     *
     * @param string $todoItemId
     * @param string $userId
     * @return void
     */
    public function permissionGuard(string $todoItemId, string $userId)
    {
        if (!$this->todo->itemExists($todoItemId)) {
            View::throw404();
        }

        if (!$this->todo->itemBelongsToUser($todoItemId, $userId)) {
            View::throw403();
        }
    }

    /**
     * Checks if given route is restricted for logged in user.
     *
     * @return boolean
     */
    private function isRestrictedRoute(string $uri): bool
    {
        $restrictedRoutes = ['/login', '/register', '/verify', '/generate-otp'];

        if (in_array($uri, $restrictedRoutes) || substr($uri, 0, 7) === '/verify') {
            return true;
        }

        return false;
    }
}