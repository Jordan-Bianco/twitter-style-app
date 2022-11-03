<?php

use App\core\Application;
?>
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