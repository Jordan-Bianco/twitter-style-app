<?php

use App\core\Application;

$user = Application::$app->session->get('user');

/** @var $this \app\core\Renderer  */
$this->title .= ' - Settings';
?>

<div class="max-w-lg mx-auto">
    <header class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-sky-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
                <h2 class="text-xl font-semibold">Settings</h2>
            </div>
            <a class="block text-zinc-500 hover:text-sky-500 bg-zinc-700 rounded-lg px-1.5 py-0.5" href="/<?= $user['username'] ?>">
                <svg class="w-5 h-5 font-semibold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </a>
        </div>
        <p class="text-zinc-500 text-xs">On this page you can update your profile information, change your password, or delete your account.</p>
    </header>

    <!-- Profile -->
    <?php require_once ROOT_PATH . '/views/users/settings/profile.view.php' ?>

    <!-- Password -->
    <?php require_once ROOT_PATH . '/views/users/settings/password.view.php' ?>

    <!-- Delete account -->
    <?php require_once ROOT_PATH . '/views/users/settings/deleteAccount.view.php' ?>
</div>