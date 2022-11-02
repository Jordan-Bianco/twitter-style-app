<?php

use App\core\Application;
use App\models\Like;

$user = Application::$app->session->get('user') ?? null;

/** @var $this \app\core\Renderer  */
$this->title .= ' - Home';
?>

<div>
    <div class="md:flex md:items-start md:space-x-8 space-y-6 md:space-y-0">

        <!-- Sidemenu -->
        <div class="md:w-1/4">
            <div class="panel">
                <div class="flex items-center space-x-3">
                    <img src="https://eu.ui-avatars.com/api/?name=<?= $user['username'] ?>" alt="user_avatar" class="w-9 h-9 rounded-lg flex-none">

                    <div>
                        <span class="block font-medium text-lime-500"><?= $user['username'] ?></span>
                        <span class="block text-xs text-zinc-500"><?= $user['email'] ?></span>
                    </div>
                </div>

                <div class="mt-6 text-xs space-y-2">
                    <a class="block" href="/<?= $user['username'] ?>">
                        Dashboard
                    </a>
                    <form action="/logout" method="POST">
                        <button type=" submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="md:w-2/4">
            <!-- Form create tweet -->
            <form action="/tweets" method="POST">

                <div class="panel">
                    <textarea class="w-full resize-none focus:outline-none text-sm bg-transparent" name="body" placeholder="What are you thinking about?" rows="4"><?= Application::$app->session->getOldData('body') ?></textarea>

                    <p class="text-red-500 font-medium text-xs mb-2">
                        <?= Application::$app->session->getValidationErrors('body') ?>
                    </p>

                    <footer class="border-t border-zinc-800">
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <img src="https://eu.ui-avatars.com/api/?name=<?= $user['username'] ?>" alt="user_avatar" class="w-7 h-7 rounded-lg flex-none">
                                <span class="font-medium block text-sm"><?= $user['username'] ?></span>
                            </div>

                            <button type="submit" class="tracking-wide bg-lime-500 hover:bg-lime-400 focus:outline-none focus:ring-2 focus:ring-lime-300 text-white px-5 py-1.5 rounded-full text-xs">
                                Tweet
                            </button>
                        </div>
                    </footer>
                </div>
            </form>

            <!-- Tweets list -->
            <?php if ($tweets) : ?>
                <div class="mt-10">
                    <?php foreach ($tweets as $tweet) : ?>
                        <div class="panel mb-3 space-y-2.5">
                            <header class="flex justify-between items-center">
                                <a href="/<?= $tweet['username'] ?>" class="flex items-center space-x-2">
                                    <img src="https://eu.ui-avatars.com/api/?name=<?= $tweet['username'] ?>" alt="user_avatar" class="w-7 h-7 rounded-lg flex-none">
                                    <span class="font-medium block text-sm">@<?= $tweet['username'] ?></span>
                                </a>

                                <?php if ($tweet['user_id'] === Application::$app->session->get('user')['id']) : ?>
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
                                <?php if (!Like::hasBeenLikedBy(Application::$app->session->get('user')['id'], $tweet['id'])) : ?>
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

                <!-- Pagination -->
                <?php if ($totalPages > 1) : ?>
                    <div class="flex items-center justify-between mt-8">
                        <div class="flex items-center justify-between space-x-2">
                            <!-- Prev button -->
                            <?php if ($currentPage > 1) : ?>
                                <a href="/tweets?page=<?= $currentPage - 1 ?>">
                                    <svg class="w-5 h-5 text-lime-500 font-semibold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            <?php endif ?>

                            <!-- Links -->
                            <div class="flex items-center space-x-2.5">
                                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                    <a class="<?= $i == $currentPage ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> block bg-zinc-900 rounded-lg px-2 py-0.5" href="/tweets?page=<?= $i ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor ?>
                            </div>

                            <!-- Next button -->
                            <?php if ($totalPages > $currentPage) : ?>
                                <a href="/tweets?page=<?= $currentPage + 1 ?>">
                                    <svg class="w-5 h-5 text-lime-500 font-semibold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            <?php endif ?>
                        </div>

                        <div>
                            <span class="text-xs text-zinc-500">
                                Page <?= $currentPage ?> of
                                <?= $totalPages ?>
                            </span>
                            <span class="text-xs text-zinc-500">&bull;</span>
                            <span class="text-xs text-zinc-500">
                                <?= $total ?> results
                            </span>

                        </div>
                    </div>
                <?php endif ?>
                <!-- End pagination -->

            <?php endif ?>
        </div>

        <div class="md:w-1/4 space-y-8">
            <!-- Top rated tweets -->
            <section>
                <div class="flex items-center space-x-2 mb-4">
                    <svg class="w-[18px] h-[18px] text-lime-500 cursor-pointer" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                    <h4 class="font-medium text-sm">Top rated Tweets</h4>
                </div>

                <?php foreach ($mostLiked as $tweet) : ?>
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
                            <?php if (!Like::hasBeenLikedBy(Application::$app->session->get('user')['id'], $tweet['id'])) : ?>
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

            <!-- Most commented tweets -->
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
                            <?php if (!Like::hasBeenLikedBy(Application::$app->session->get('user')['id'], $tweet['id'])) : ?>
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
        </div>
    </div>
</div>