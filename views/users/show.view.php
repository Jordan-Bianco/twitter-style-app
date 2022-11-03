<?php

use App\core\Application;
use App\models\Follow;
use App\models\Like;

/** @var $this \app\core\Renderer  */
$this->title .= " - " . $user['username'];
?>
<div class="max-w-lg mx-auto">
    <header class="mb-6">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center space-x-4">
                <img src="https://eu.ui-avatars.com/api/?name=<?= $user['username'] ?>" alt="user_avatar" class="w-14 h-14 rounded-lg flex-none">

                <div>
                    <span class="block text-lg font-medium mb-1">@<?= $user['username'] ?></span>
                    <span class="block text-xs text-zinc-500"><?= $user['created_at'] ?></span>
                </div>
            </div>

            <div>
                <!-- If you are logged in and it is your profile -->
                <?php if (Application::$app->session->authId() === $user['id']) : ?>
                    <div class="text-xs text-zinc-500">
                        <a href="/settings" class="hover:text-sky-500">Settings</a>
                    </div>
                <?php else : ?>

                    <?php if (!Follow::requestStatus($user['id'])) : ?>
                        <!-- If the follow request has not been sent -->
                        <form action="/users/<?= $user['id'] ?>/follow" method="post">
                            <button type="submit" class="tracking-wide bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300 text-white px-5 py-1.5 rounded-full text-xs">
                                Follow
                            </button>
                        </form>
                    <?php elseif (Follow::requestStatus($user['id']) === 'Pending') : ?>
                        <!-- If it has been sent, and it is pending -->
                        <form action="/users/<?= $user['id'] ?>/unfollow" method="post">
                            <button type="submit" class="tracking-wide bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300 text-white px-5 py-1.5 rounded-full text-xs">
                                Request sent
                            </button>
                        </form>
                    <?php elseif (Follow::requestStatus($user['id']) === 'Declined') : ?>
                        <!-- If it has been sent, and it has been rejected -->
                        <span class="block cursor-default tracking-wide bg-zinc-400 focus:outline-none text-white px-5 py-1.5 rounded-full text-xs">
                            Request declined
                        </span>
                    <?php else : ?>
                        <!-- If it has been sent and accepted -->
                        <form action="/users/<?= $user['id'] ?>/unfollow" method="post">
                            <button type="submit" class="tracking-wide bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300 text-white px-5 py-1.5 rounded-full text-xs">
                                Unfollow
                            </button>
                        </form>
                    <?php endif ?>

                <?php endif ?>
            </div>
        </div>

        <p class="text-zinc-300 mb-3">
            <?= $user['bio'] ?? '' ?>
        </p>

        <div class="flex items-center justify-around border-b border-t border-zinc-700 py-3">
            <div class="flex flex-col items-center">
                <span class="block text-sky-500 font-semibold text-base"><?= $user['tweets_count'] ?></span>
                <span class="text-xs">Tweets</span>
            </div>
            <div class="flex flex-col items-center">
                <a href="/<?= $user['username'] ?>/followers" class="block text-sky-500 font-semibold text-base"><?= $user['followers_count'] ?></a>
                <span class="text-xs">Followers</span>
            </div>
            <div class="flex flex-col items-center">
                <a href="/<?= $user['username'] ?>/following" class="block text-sky-500 font-semibold text-base"><?= $user['following_count'] ?></a>
                <span class="text-xs">Following</span>
            </div>
        </div>
    </header>

    <!-- Tweets -->
    <?php require_once ROOT_PATH . '/views/users/tweets.view.php' ?>
</div>