<?php

use App\core\Application;

$user = Application::$app->session->get('user');

/** @var $this \app\core\Renderer  */
$this->title .= ' - Impostazioni';
?>

<!-- aggiornare password -->

<div class="max-w-lg mx-auto">
    <header class="mb-10">
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-lime-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
                <h2 class="text-xl font-semibold">Impostazioni</h2>
            </div>
            <a class="block hover:text-lime-500" href="/<?= $user['username'] ?>">
                <svg class="w-5 h-5 text-lime-500 font-semibold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </a>
        </div>
        <p class="text-zinc-500 text-xs">In questa pagina puoi aggiornare le informazioni del tuo profilo, cambiare la password, o cancellare il tuo account.</p>
    </header>

    <!-- Profile -->
    <div class="border-t border-zinc-700 pt-8">
        <form action="/settings" method="POST">

            <div class="mb-6 flex items-start justify-between space-x-10">
                <label for="username" class="block mb-1 text-zinc-300 text-xs w-1/4">
                    Username
                </label>

                <div class="w-3/4">
                    <input name="username" placeholder="Username" type="text" value="<?= $user['username'] ?>" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">

                    <p class="text-red-500 font-medium text-xs mt-1">
                        <?= Application::$app->session->getValidationErrors('username') ?>
                    </p>
                </div>
            </div>

            <div class="mb-6 flex items-start justify-between space-x-10">
                <label for="bio" class="block mb-1 text-zinc-300 text-xs w-1/4">
                    Bio
                </label>

                <div class="w-3/4">
                    <textarea name="bio" placeholder="Parlaci un po' di te..." class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition resize-none" rows="5"><?= $user['bio'] ?? Application::$app->session->getOldData('bio') ?></textarea>

                    <p class="text-red-500 font-medium text-xs mt-1">
                        <?= Application::$app->session->getValidationErrors('bio') ?>
                    </p>
                </div>
            </div>

            <footer class="flex justify-end">
                <button type="submit" class="tracking-wide bg-lime-500 hover:bg-lime-400 focus:outline-none focus:ring-2 focus:ring-lime-300 text-white px-5 py-1.5 rounded-full text-xs">
                    Aggiorna
                </button>
            </footer>
        </form>
    </div>

    <!-- Password -->
    <div class="border-t border-zinc-700 mt-10 pt-8">
        <p class="text-zinc-500 text-xs mb-6">La nuova password deve essere lunga almeno 8 caratteri, contenere un numero ed un carattere speciale.</p>

        <form action="/update-password" method="POST">

            <div class="mb-6 flex items-start justify-between space-x-10">
                <label for="current_password" class="block mb-1 text-zinc-300 text-xs w-1/4">
                    Password attuale
                </label>

                <div class="w-3/4">
                    <input name="current_password" placeholder="Inserisci la tua password attuale" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">

                    <p class="text-red-500 font-medium text-xs mt-1">
                        <?= Application::$app->session->getValidationErrors('current_password') ?>
                    </p>
                </div>
            </div>

            <div class="mb-6 flex items-start justify-between space-x-10">
                <label for="new_password" class="block mb-1 text-zinc-300 text-xs w-1/4">
                    Nuova password
                </label>

                <div class="w-3/4">
                    <input name="new_password" placeholder="Inserisci la nuova password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">

                    <p class="text-red-500 font-medium text-xs mt-1">
                        <?= Application::$app->session->getValidationErrors('new_password') ?>
                    </p>
                </div>
            </div>

            <div class="mb-6 flex items-start justify-between space-x-10">
                <label for="password_confirm" class="block mb-1 text-zinc-300 text-xs w-1/4">
                    Conferma password
                </label>

                <div class="w-3/4">
                    <input name="password_confirm" placeholder="Conferma la nuova password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">

                    <p class="text-red-500 font-medium text-xs mt-1">
                        <?= Application::$app->session->getValidationErrors('password_confirm') ?>
                    </p>
                </div>
            </div>


            <footer class="flex justify-end">
                <button type="submit" class="tracking-wide bg-lime-500 hover:bg-lime-400 focus:outline-none focus:ring-2 focus:ring-lime-300 text-white px-5 py-1.5 rounded-full text-xs">
                    Aggiorna
                </button>
            </footer>
        </form>
    </div>

    <!-- Delete account -->
    <div class="border-t border-zinc-700 mt-10 pt-8">
        <div class="flex items-start justify-between space-x-10">
            <div>
                <span class="block mb-2">Cancella il mio account</span>
                <span class="block text-xs text-zinc-500">Cancellando il mio account, verranno eliminati tutti i tuoi tweets, i commenti e i likes.</span>
            </div>
            <a href="/delete-account" class="tracking-wide text-red-500 border border-red-500 hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-300 px-5 py-1.5 rounded-full text-xs">Cancella</a>
        </div>
    </div>
</div>