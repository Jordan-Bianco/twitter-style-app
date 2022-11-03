<?php

use App\core\Application;

/** @var $this \app\core\Renderer  */
$this->title .= ' - Login';
?>

<div class="max-w-[350px] mx-auto mt-10">
    <section class="p-6 border border-zinc-700 shadow-md shadow-zinc-900 rounded-lg">
        <h2 class="text-center text-3xl font-medium mb-2.5">Login</h2>
        <p class="text-center text-xs text-zinc-400 mb-6">Not registered yet?
            <span class="text-sky-500"><a href="/register">Sign in</a></span>
        </p>

        <!-- ValidationErrors -->
        <?php require_once ROOT_PATH . '/views/partials/validationErrors.php' ?>

        <form action="login" method="POST">
            <div class="mb-6">
                <label for="email" class="block mb-1 text-zinc-300 text-xs">
                    Email
                </label>
                <input name="email" placeholder="Example@email.com" type="email" value="<?= Application::$app->session->getOldData('email') ?>" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <div class="mb-2">
                <label for="password" class="block mb-1 text-zinc-300 text-xs">
                    Password
                </label>
                <input name="password" placeholder="Enter your password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>
            <a class="block text-xs text-zinc-400 hover:underline mb-8 max-w-max" href="/forgot-password">Forgot password?</a>

            <button type="submit" class="tracking-wide w-full bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300 text-white p-3 rounded-lg text-xs">
                Log in
            </button>
        </form>
    </section>
</div>