<?php

use App\core\Application;
use App\core\Session;
use App\models\Like;

?>
<div class="max-w-lg mx-auto">
    <header class="mb-10">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="https://eu.ui-avatars.com/api/?name=<?= $user['username'] ?>" alt="user_avatar" class="w-12 h-12 rounded-lg flex-none">

                <div>
                    <h2 class="text-lg font-semibold">
                        <?= '@' . $user['username'] ?>
                    </h2>
                    <span class="text-sm text-zinc-500">
                        <?= $user['email'] ?>
                    </span>
                    <span class="text-xs text-zinc-500">&bull;</span>
                    <span class="text-xs text-lime-500 font-medium">
                        <?= $user['created_at'] ?>
                    </span>

                </div>
            </div>

            <?php if (Session::isLoggedIn() && Application::$app->session->get('user')['id'] === $user['id']) : ?>
                <div class="text-xs text-zinc-500">
                    <a href="/settings" class="hover:text-lime-500">Impostazioni</a>
                </div>
            <?php endif ?>
        </div>
        <p class="text-xs text-zinc-300 mt-6">
            <?= $user['bio'] ?? '' ?>
        </p>
    </header>

    <?php if ($tweets) : ?>
        <div class="mt-8">
            <?php foreach ($tweets as $tweet) : ?>
                <div class="panel mb-3 space-y-2.5">
                    <header class="flex justify-between items-center mb-2.5">
                        <div class="flex items-center space-x-2">
                            <img src="https://eu.ui-avatars.com/api/?name=<?= $user['username'] ?>" alt="user_avatar" class="w-7 h-7 rounded-lg flex-none">
                            <span class="font-medium block text-sm">@<?= $user['username'] ?></span>
                        </div>

                        <?php if (Session::isLoggedIn() && $tweet['user_id'] === Application::$app->session->get('user')['id']) : ?>
                            <div class="flex items-center space-x-1">
                                <!-- Edit -->
                                <a href="/tweets/<?= $tweet['id'] ?>/edit">
                                    <svg class="w-[18px] h-[18px] text-zinc-500 hover:text-zinc-400 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <!-- Delete -->
                                <form action="/tweets/<?= $tweet['id'] ?>/delete" method="post" class="flex">
                                    <button type="submit">
                                        <svg class="w-[18px] h-[18px] text-zinc-500 hover:text-zinc-400 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        <?php endif ?>
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
                        <?php if (!Like::hasBeenLikedBy(Application::$app->session->get('user')['id'] ?? null, $tweet['id'])) : ?>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle w-[18px] h-[18px] text-zinc-500">
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
        </div>

        <!-- Paginazione -->
        <?php if ($totalPages > 1) : ?>
            <div class="flex items-center justify-between mt-8">
                <div class="flex items-center justify-between space-x-2">
                    <!-- Prev button -->
                    <?php if ($currentPage > 1) : ?>
                        <a href="/<?= $user['username'] ?>?page=<?= $currentPage - 1 ?>">
                            <svg class="w-5 h-5 text-lime-500 font-semibold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    <?php endif ?>


                    <!-- Links -->
                    <div class="flex items-center space-x-2.5">
                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <a class="<?= $i == $currentPage ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> block bg-zinc-900 rounded-lg px-2 py-0.5" href="/<?= $user['username'] ?>?page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor ?>
                    </div>

                    <!-- Next button -->
                    <?php if ($totalPages > $currentPage) : ?>
                        <a href="/<?= $user['username'] ?>?page=<?= $currentPage + 1 ?>">
                            <svg class="w-5 h-5 text-lime-500 font-semibold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    <?php endif ?>
                </div>

                <div>
                    <span class="text-xs text-zinc-500">
                        Pagina <?= $currentPage ?> di
                        <?= $totalPages ?>
                    </span>
                    <span class="text-xs text-zinc-500">&bull;</span>
                    <span class="text-xs text-zinc-500">
                        <?= $total ?> risultati
                    </span>

                </div>
            </div>
        <?php endif ?>
        <!-- Fine paginazione -->

    <?php endif ?>
</div>