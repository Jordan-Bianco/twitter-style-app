<?php

use App\core\Application;

/** @var $this \app\core\Renderer  */
$this->title .= ' - Followers';
?>

<div class="max-w-lg mx-auto">
    <h2 class="mb-8 font-medium text-xl"><?= $username ?> Followers</h2>

    <?php if (Application::$app->session->get('user')['username'] === $username) : ?>
        <div class="border-b border-zinc-700 pb-4 mb-8 flex items-center justify-around">
            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'pending' || !isset($_GET['status']) ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/followers?status=pending">Pending</a>

            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'accepted' ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/followers?status=accepted">Accepted</a>

            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'declined' ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/followers?status=declined">Declined</a>
        </div>
    <?php endif ?>

    <?php foreach ($followers as $follower) : ?>
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-4">
                <img src="https://eu.ui-avatars.com/api/?name=<?= $follower['username'] ?>" alt="user_avatar" class="w-8 h-8 rounded-lg flex-none">
                <div>
                    <a href="/<?= $follower['username'] ?>" class="hover:text-lime-500">@<?= $follower['username'] ?></a>
                </div>
            </div>

            <div class="space-x-1">
                <?php if (Application::$app->session->get('user')['id'] === $follower['following_id']) : ?>

                    <?php if ($follower['status'] !== 'Declined') : ?>
                        <!-- If the request is in Pending status, I can accept or decline -->
                        <?php if ($follower['status'] === 'Pending') : ?>
                            <div class="flex items-center space-x-2">
                                <form action="/followers/<?= $follower['id'] ?>/accept" method="post">
                                    <footer class="flex justify-end">
                                        <button class="hover:text-lime-500" type="submit">
                                            Accept
                                        </button>
                                    </footer>
                                </form>
                                <form action="/followers/<?= $follower['id'] ?>/decline" method="post">
                                    <footer class="flex justify-end">
                                        <button class="hover:text-red-500" type="submit">
                                            Decline
                                        </button>
                                    </footer>
                                </form>
                            </div>
                        <?php elseif ($follower['status'] === 'Accepted') : ?>
                            <!-- If it's in the Accepted status, I can remove the follow -->
                            <form action="/followers/<?= $follower['id'] ?>/remove" method="post">
                                <footer class="flex justify-end">
                                    <button class="hover:text-red-500" type="submit">
                                        Remove
                                    </button>
                                </footer>
                            </form>
                        <?php endif ?>

                    <?php endif ?>

                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
</div>