<?php

use App\core\Application;
?>

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
                <textarea name="bio" placeholder="Tell us a little about yourself..." class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition resize-none" rows="5"><?= $user['bio'] ?? Application::$app->session->getOldData('bio') ?></textarea>

                <p class="text-red-500 font-medium text-xs mt-1">
                    <?= Application::$app->session->getValidationErrors('bio') ?>
                </p>
            </div>
        </div>

        <footer class="flex justify-end">
            <button type="submit" class="tracking-wide bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300 text-white px-5 py-1.5 rounded-full text-xs">
                Update
            </button>
        </footer>
    </form>
</div>