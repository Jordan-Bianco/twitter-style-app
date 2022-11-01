<?php

use App\core\Application;
use App\core\Session;
?>

<div class="max-w-lg mx-auto">
    <?php if (Session::isLoggedIn() && Application::$app->session->get('user')['username'] === $username) : ?>
        <div class="border-b border-zinc-700 pb-4 mb-8 flex items-center justify-around">
            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'pending' || !isset($_GET['status']) ? 'text-lime-500' : 'text-zinc-500' ?>" href="/<?= $username ?>/following?status=pending">In attesa</a>

            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'accepted' ? 'text-lime-500' : 'text-zinc-500' ?>" href="/<?= $username ?>/following?status=accepted">Accettate</a>

            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'declined' ? 'text-lime-500' : 'text-zinc-500' ?>" href="/<?= $username ?>/following?status=declined">Respinte</a>
        </div>
    <?php endif ?>

    <?php foreach ($followings as $following) : ?>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <img src="https://eu.ui-avatars.com/api/?name=<?= $following['username'] ?>" alt="user_avatar" class="w-9 h-9 rounded-lg flex-none">
                <a href="/<?= $following['username'] ?>" class="hover:text-lime-500">@<?= $following['username'] ?></a>
            </div>

            <?php if (Session::isLoggedIn() && Application::$app->session->get('user')['id'] === $following['follower_id']) : ?>
                <span><?= $following['status'] ?></span>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</div>