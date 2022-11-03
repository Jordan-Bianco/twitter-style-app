<?php

use App\core\Application;
use App\models\Like;
?>

<section>
    <div class="flex items-center space-x-2 mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle w-[18px] h-[18px] text-lime-500">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
            </path>
        </svg>
        <h4 class="font-medium text-sm">Most commented tweets</h4>
    </div>

    <?php foreach ($mostCommented as $tweet) : ?>
        <div class="panel mb-3 space-y-2">
            <header>
                <a href="/<?= $tweet['username'] ?>" class="flex items-center space-x-2">
                    <img src="https://eu.ui-avatars.com/api/?name=<?= $tweet['username'] ?>" alt="user_avatar" class="w-6 h-6 rounded-lg flex-none">
                    <span class="font-medium block text-sm">@<?= $tweet['username'] ?></span>
                </a>
            </header>

            <p class="text-zinc-400 text-xs">
                <a href="/tweets/<?= $tweet['id'] ?>">
                    <?= $tweet['body'] ?>
                </a>
            </p>

            <p class="text-[11px] text-lime-500 font-medium">
                <?= $tweet['created_at'] ?>
            </p>

            <footer class="flex items-center space-x-3">
                <?php if (!Like::hasBeenLikedBy(Application::$app->session->authId(), $tweet['id'])) : ?>
                    <form action="/like/<?= $tweet['id'] ?>/add" method="POST" class="flex items-center space-x-0.5">
                        <button type="submit">
                            <svg class="w-[18px] h-[18px] text-zinc-500 hover:text-red-500 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>
                        <span class="text-xs font-medium">
                            <?= $tweet['likes_count'] ?>
                        </span>
                    </form>
                <?php else : ?>
                    <form action="/like/<?= $tweet['id'] ?>/remove" method="POST" class="flex items-center space-x-0.5">
                        <button type="submit">
                            <svg class="w-[18px] h-[18px] text-red-500 hover:text-red-600 cursor-pointer" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>
                        <span class="text-xs font-medium">
                            <?= $tweet['likes_count'] ?>
                        </span>
                    </form>
                <?php endif ?>

                <div class="flex items-center space-x-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle w-4 h-4 text-zinc-500">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                        </path>
                    </svg>
                    <span class="block text-xs font-medium">
                        <?= $tweet['comments_count'] ?>
                    </span>
                </div>
            </footer>
        </div>
    <?php endforeach ?>
</section>