<?php

namespace App\Controllers;

use Core\Session;
use Core\Container;
use App\Middlewares\Auth;

class LoginController extends Controller
{
    use Auth;

    private $user;
    private $validator;

    public function __construct(Container $container)
    {
        $this->user = $container->get('user');
        $this->validator = $container->get('validator');

        $this->auth();
    }

    /**
     * Display the login form.
     *
     * @return void
     */
    public function show()
    {
        $this->render('login', [
            'pageTitle' => 'Login',
        ]);
    }

    /**
     * Login the user.
     *
     * @param $request
     * @return void
     */
    public function login($request)
    {
        $errors = $this->validator->make($request, [
            'email' => ['required'],
            'password' => ['required']
        ]);

        if (!empty($errors)) {
            $this->redirect('/login', [
                'errors' => $errors
            ]);
        }

        $this->loginUser($request['email'], $request['password']);
    }

    /**
     * Verify the credentials and login the user.
     *
     * @param string $email
     * @param string $password
     * @return void
     */
    private function loginUser(string $email, string $password)
    {
        if (!$this->user->validateCredentials($email, $password)) {
            $this->redirect('/login', [
                'message' => 'Invalid credentials'
            ]);
        }

        $userId = $this->user->id($email);

        if (!$this->user->isVerified($userId)) {
            $this->redirect('/login', [
                'message' => 'Please verify your email first.'
            ]);
        }

        Session::set('user', [
            'id' => $userId
        ]);

        $this->redirect('/');
    }

    /**
     * Logout the user.
     *
     * @return void
     */
    public function logout()
    {
        Session::unset('user');
        $this->redirect('/login');
    }
}