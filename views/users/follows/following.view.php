<?php

use App\core\Application;

/** @var $this \app\core\Renderer  */
$this->title .= ' - Following';
?>

<div class="max-w-lg mx-auto">
    <h2 class="mb-8 font-medium text-xl"><?= $username ?> Following</h2>

    <?php if (Application::$app->session->get('user')['username'] === $username) : ?>
        <div class="border-b border-zinc-700 pb-4 mb-8 flex items-center justify-around">
            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'pending' || !isset($_GET['status']) ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/following?status=pending">Pending</a>

            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'accepted' ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/following?status=accepted">Accepted</a>

            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'declined' ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/following?status=declined">Declined</a>
        </div>
    <?php endif ?>

    <?php foreach ($followings as $following) : ?>
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-4">
                <img src="https://eu.ui-avatars.com/api/?name=<?= $following['username'] ?>" alt="user_avatar" class="w-8 h-8 rounded-lg flex-none">
                <div>
                    <a href="/<?= $following['username'] ?>" class="hover:text-lime-500">@<?= $following['username'] ?></a>
                </div>
            </div>

            <div class="space-x-1">
                <?php if (Application::$app->session->get('user')['id'] === $following['follower_id']) : ?>

                    <?php if ($following['status'] !== 'Declined') : ?>
                        <form action="/following/<?= $following['id'] ?>/remove" method="post">
                            <footer class="flex justify-end">
                                <button class="hover:text-red-500" type="submit">
                                    <?= $following['status'] === 'Pending' ? 'Cancel' : 'Unfollow' ?>
                                </button>
                            </footer>
                        </form>
                    <?php endif ?>

                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
</div>