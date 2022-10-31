<?php

use App\core\Application;
use App\core\Session;

?>
<div class="max-w-lg mx-auto">
    <header class="mb-6">
        <h2 class="text-base font-semibold">Modifica Commento</h2>
    </header>

    <form action="/comments/<?= $comment['id'] ?>/update" method="POST">
        <div class="panel space-y-2">

            <textarea class="w-full bg-transparent resize-none focus:outline-none text-sm" name="body" placeholder="A che cosa stai pensando?" rows="4"><?= $comment['body'] ?></textarea>

            <p class="text-red-500 font-medium text-xs mb-2">
                <?= Application::$app->session->getValidationErrors('body') ?>
            </p>

            <footer class="border-t border-zinc-800">
                <div class="mt-3 flex items-center justify-between">
                    <span class="font-medium text-xs block">
                        <?= Session::isLoggedIn() ? '@' . Application::$app->session->get('user')['username'] : ''; ?>
                    </span>

                    <button type="submit" class="tracking-wide bg-lime-500 hover:bg-lime-400 focus:outline-none focus:ring-2 focus:ring-lime-300 text-white px-5 py-1.5 rounded-full text-xs">
                        Modifica commento
                    </button>
                </div>
            </footer>
        </div>
    </form>
</div>