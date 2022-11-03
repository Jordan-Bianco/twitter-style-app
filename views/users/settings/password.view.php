<?php

use App\core\Application;
?>
<div class="border-t border-zinc-700 mt-10 pt-8">
    <p class="text-zinc-500 text-xs mb-6">The new password must be at least 8 characters long, contain a number and a special character.</p>

    <form action="/update-password" method="POST">

        <div class="mb-6 flex items-start justify-between space-x-10">
            <label for="current_password" class="block mb-1 text-zinc-300 text-xs w-1/4">
                Current password
            </label>

            <div class="w-3/4">
                <input name="current_password" placeholder="Enter your current password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">

                <p class="text-red-500 font-medium text-xs mt-1">
                    <?= Application::$app->session->getValidationErrors('current_password') ?>
                </p>
            </div>
        </div>

        <div class="mb-6 flex items-start justify-between space-x-10">
            <label for="new_password" class="block mb-1 text-zinc-300 text-xs w-1/4">
                New password
            </label>

            <div class="w-3/4">
                <input name="new_password" placeholder="Enter your new password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">

                <p class="text-red-500 font-medium text-xs mt-1">
                    <?= Application::$app->session->getValidationErrors('new_password') ?>
                </p>
            </div>
        </div>

        <div class="mb-6 flex items-start justify-between space-x-10">
            <label for="password_confirm" class="block mb-1 text-zinc-300 text-xs w-1/4">
                Confirm password
            </label>

            <div class="w-3/4">
                <input name="password_confirm" placeholder="Confirm your new password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">

                <p class="text-red-500 font-medium text-xs mt-1">
                    <?= Application::$app->session->getValidationErrors('password_confirm') ?>
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