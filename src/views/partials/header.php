<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/main.css">
    <title><?php e($data['pageTitle']) ?> - <?php e(SITE_TITLE) ?></title>
</head>
<body>
    <header class="content-wrapper">
        <h1 class="heading center">
            <a class="link" href="/">Todo List</a>
        </h1>
        <?php require VIEWS_DIR . 'partials/nav.php' ?>
    </header>
    <main class="content-wrapper">