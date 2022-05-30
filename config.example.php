<?php

// site setting
define('APP_KEY', '');
define('SITE_URL', $_SERVER['HTTP_HOST']);
define('DEVELOPMENT', true);
define('EMAIL_EXPIRY_TIME', 30);  // in minutes

// paths
define('SITE_TITLE', 'TODO');
define('PROJECT_DIR', __DIR__);
define('VIEWS_DIR', PROJECT_DIR . '/src/views/');

// class path
define('CONTROLLERS_CLASS_PATH', 'App\\Controllers\\');

// database config
define('DB_HOST', 'localhost');
define('DB_NAME', 'todo_php');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');