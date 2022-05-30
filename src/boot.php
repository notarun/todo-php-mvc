<?php

if (!file_exists('../config.php')) {
    throw new Exception('No config file found.');
}

// load up the config file
require '../config.php';

if (!DEVELOPMENT) {
    error_reporting(0);
}

// composer autoload magic
require PROJECT_DIR . '/vendor/autoload.php';

// load up helper functions
require PROJECT_DIR . '/src/functions.php';

// routes setup
Core\Router::load(PROJECT_DIR . '/src/routes.php')->run();

// validator setup
Core\Validator::load(PROJECT_DIR . '/src/validators.php');