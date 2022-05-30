<?php

// database container
$container->add('database', new Core\Database(DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]));

// models
$container->add('todo', new App\Models\Todo($container->get('database')));
$container->add('user', new App\Models\User($container->get('database')));

// validator
$container->add('validator', Core\Validator::load(PROJECT_DIR . '/src/validators.php'));