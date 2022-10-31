<?php

use App\core\Application;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= Application::$app->renderer->title ?>
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/style.css">
</head>

<body class="text-sm text-zinc-100 bg-zinc-800 tracking-wide">

    <?php require_once(ROOT_PATH . '/views/layouts/navbar.php') ?>

    <div class="p-6 max-w-6xl mx-auto">

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="fixed top-[68px] right-10 bg-lime-200 text-lime-600 p-4 rounded-xl shadow-md shadow-zinc-900 z-20">
                <?php Application::$app->session->getFlashMessage('success') ?>
            </div>
        <?php endif ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="fixed top-[68px] right-10 bg-red-200 text-red-600 p-4 rounded-xl shadow-md shadow-zinc-900 z-20">
                <?php Application::$app->session->getFlashMessage('error') ?>
            </div>
        <?php endif ?>

        {{ content }}
    </div>
</body>

</html>