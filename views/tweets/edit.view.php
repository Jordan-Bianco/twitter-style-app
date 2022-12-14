<?php

use App\core\Application;

/** @var $this \app\core\Renderer  */
$this->title .= ' - Edit tweet';
?>
<div class="max-w-lg mx-auto">
    <header class="mb-6">
        <h2 class="text-base font-semibold">Edit Tweet</h2>
    </header>

    <form action="/tweets/<?= $tweet['id'] ?>/update" method="POST">
        <div class="panel space-y-2">

            <textarea class="w-full bg-transparent resize-none focus:outline-none text-sm" name="body" placeholder="What are you thinking about?" rows="4"><?= $tweet['body'] ?></textarea>

            <p class="text-red-500 font-medium text-xs mb-2">
                <?= Application::$app->session->getValidationErrors('body') ?>
            </p>

            <footer class="border-t border-zinc-800">
                <div class="mt-3 flex items-center justify-between">
                    <span class="font-medium text-xs block">
                        @<?= Application::$app->session->get('user')['username'] ?>
                    </span>

                    <button type="submit" class="tracking-wide bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300 text-white px-5 py-1.5 rounded-full text-xs">
                        Edit tweet
                    </button>
                </div>
            </footer>
        </div>
    </form>
</div>