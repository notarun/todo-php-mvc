<?php

$router->get('/', 'TodoController@show');
$router->get('/:id', 'TodoController@show');

$router->post('/add', 'TodoController@add');
$router->post('/:id/update', 'TodoController@update');
$router->post('/:id/done', 'TodoController@done');
$router->post('/:id/undone', 'TodoController@undone');
$router->post('/:id/delete', 'TodoController@delete');

$router->get('/login', 'LoginController@show');
$router->post('/login', 'LoginController@login');
$router->post('/logout', 'LoginController@logout');

$router->get('/register', 'RegistrationController@show');
$router->post('/register', 'RegistrationController@register');

$router->get('/verify/:verification-code', 'RegistrationController@emailForm');
$router->post('/verify/:verification-code', 'RegistrationController@emailVerify');

$router->get('/generate-otp', 'RegistrationController@emailForm');
$router->post('/generate-otp', 'RegistrationController@generateOtp');