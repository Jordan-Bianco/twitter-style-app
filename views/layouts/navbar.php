<?php

use App\core\Application;
use App\core\Session;

?>

<nav class="md:px-8 px-4 py-4 flex items-center justify-between space-x-6 mb-3">
    <div class="flex items-center space-x-4 md:w-1/4 max-w-max">
        <a href="/" class="font-bold tracking-tight text-lg flex items-center space-x-2">
            <svg class="w-6 h-6 text-lime-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd"></path>
            </svg>
            <h1><?= $_ENV['SITE_NAME'] ?></h1>
        </a>
    </div>

    <div class="md:w-2/4 w-full">
        <form action="/users" method="get" class="relative">
            <input type="text" placeholder="Search users by email or username..." name="search" class="w-full text-xs pr-4 pl-12 py-3 panel rounded-full focus:outline-none focus:ring-2 focus:ring-zinc-600 transition">
            <svg class="w-5 h-5 text-lime-500 absolute top-2.5 left-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
            </svg>
        </form>
    </div>

    <div class="md:w-1/4 max-w-max flex items-center justify-end space-x-2">
        <?php if (!Session::isLoggedIn()) : ?>
            <div class="flex items-center space-x-2">
                <a href="/login" class="block hover:text-lime-500 <?= Application::$app->request->getUri() === '/login' ? 'text-lime-500' : '' ?>">Log in</a>
                <a href="/register" class="block hover:text-lime-500 <?= Application::$app->request->getUri() == '/register' ? 'text-lime-500' : '' ?>">Sign in</a>
            </div>
        <?php else : ?>
            <a class="hover:text-lime-500" href="/<?= Application::$app->session->get('user')['username'] ?>">
                <?= Application::$app->session->get('user')['username'] ?>
            </a>
            <form action="/logout" method="POST" class="flex">
                <button type=" submit">
                    <svg class="w-5 h-5 text-lime-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        <?php endif ?>
    </div>
</nav>