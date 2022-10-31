<?php

use App\core\Application;

/** @var $this \app\core\Renderer  */
$this->title .= ' - Registrati';
?>

<div class="max-w-[350px] mx-auto mt-10">
    <section class="p-6 border border-zinc-700 shadow-md shadow-zinc-900 rounded-lg">
        <h2 class="text-center text-3xl font-medium mb-2.5">Sign up</h2>
        <p class="text-center text-xs text-zinc-400 mb-6">Già iscritto?
            <span class="text-lime-500"><a href="/login">Accedi</a></span>
        </p>

        <!-- ValidationErrors -->
        <?php require_once ROOT_PATH . '/views/partials/validationErrors.php' ?>

        <form action="register" method="POST">
            <div class="mb-6">
                <label for="username" class="block mb-1 text-zinc-300 text-xs">
                    Username
                </label>
                <input name="username" placeholder="Username" type="text" value="<?= Application::$app->session->getOldData('username') ?>" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <div class="mb-6">
                <label for="email" class="block mb-1 text-zinc-300 text-xs">
                    Email
                </label>
                <input name="email" placeholder="Example@email.com" type="email" value="<?= Application::$app->session->getOldData('email') ?>" class=" w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-1 text-zinc-300 text-xs">
                    Password
                </label>
                <input name="password" placeholder="Inserisci la tua password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <div class="mb-8">
                <label for="password_confirm" class="block mb-1 text-zinc-300 text-xs">
                    Conferma Password
                </label>
                <input name="password_confirm" placeholder="Conferma la tua password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <button type="submit" class="tracking-wide w-full bg-lime-500 hover:bg-lime-400 focus:outline-none focus:ring-2 focus:ring-lime-300 text-white p-3 rounded-lg text-xs">
                Registrati
            </button>
        </form>
    </section>
</div>