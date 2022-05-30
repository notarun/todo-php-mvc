<?php

namespace App\Controllers;

use Core\Otp;
use Core\Csrf;
use Core\Mail;
use Core\Container;
use App\Middlewares\Auth;

class RegistrationController extends Controller
{
    use Auth;

    private $user;
    private $validator;

    public function __construct(Container $container)
    {
        $this->user = $container->get('user');
        $this->validator = $container->get('validator');

        // authentication middleware
        $this->auth();
    }

    /**
     * Display the registration form.
     *
     * @return void
     */
    public function show()
    {
        $this->render('register', [
            'pageTitle' => 'Register',
        ]);
    }

    public function register($request)
    {
        $request['name'] = trim($request['name']);
        $request['email'] = trim($request['email']);

        $errors = $this->validator->make($request, [
            'name' => ['required'],
            'password' => ['required'],
            'email' => [
                'required',
                'email',
                fn ($input) => $this->user->emailExists($input) ? 'Email already exists' : null
            ],
            'repeat-password' => [
                'required',
                fn ($input, $request) => ($input !== $request['password']) ? 'This field must be same as the password field.' : null
            ]
        ]);

        if (!empty($errors)) {
            $this->redirect('/register', [
                'errors' => $errors
            ]);
        }

        $this->user->add(
            $request['name'],
            $request['email'],
            $request['password']
        );

        $this->sendVerificationEmail([
            'name' => $request['name'],
            'email' => $request['email'],
        ], Otp::generate($request['email']));

        $this->redirect('/login', [
            'message' => 'Please verify you email before logging in.'
        ]);
    }

    /**
     * Display the email form.
     *
     * @return void
     */
    public function emailForm()
    {
        $this->render('email-form', [
            'pageTitle' => 'Verifiy your email',
        ]);
    }

    /**
     * Verify the user's email.
     *
     * @return void
     */
    public function emailVerify($request, $params)
    {
        $request['email'] = trim($request['email']);

        $errors = $this->validator->make($request, [
            'email' => ['required', 'email']
        ]);

        if (!empty($errors)) {
            $this->back([
                'errors' => $errors
            ]);
        }

        if (!Otp::verify($request['email'], $params['verification-code'])) {
            $this->back([
                'message' => 'Verification Failed.',
                'verificationFailed' => true
            ]);
        }

        $this->user->markVerified($this->user->id($request['email']));

        $this->redirect('/login', [
            'message' => 'Verification Successfull. Please Login'
        ]);
    }

    public function generateOtp($request, $params)
    {
        $request['email'] = trim($request['email']);

        $errors = $this->validator->make($request, [
            'email' => ['required', 'email']
        ]);

        if (!empty($errors)) {
            $this->back([
                'errors' => $errors
            ]);
        }

        if ($this->user->emailExists($request['email'])) {
            $this->sendVerificationEmail([
                'name' => $this->user->name($this->user->id($request['email'])),
                'email' => $request['email'],
            ], Otp::generate($request['email']));
        }

        $this->back([
            'message' => 'If that email address is in our database, we will send you an email to reset your password.',
        ]);
    }

    private function sendVerificationEmail(array $data, string $verificationCode)
    {
        $verificationLink = 'http://' . SITE_URL . '/verify/' . $verificationCode;

        $name = $data['name'];
        $to = $data['email'];
        $subject = 'Email verification - Todo List';

        $message = <<<EOT
        Hey ${name},
        Thank you for registering on Todo List.
        You can verify your email by clicking on the given link below.
        This link will expire in 30 minutes.
        <br/>
        <a href="${verificationLink}">${verificationLink}</a>
        <br/>
        EOT;

        Mail::send($to, $subject, $message, [
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/html; charset=iso-8859-1',
            'From' => 'Todo Email <email@todo.local>',
        ]);
    }
}