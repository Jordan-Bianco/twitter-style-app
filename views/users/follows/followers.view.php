<?php

use App\core\Application;
use App\core\Session;
?>
<div class="max-w-lg mx-auto">
    <?php if (count($followers) > 0) : ?>
        <?php foreach ($followers as $follower) : ?>
            <div class="mb-4">
                <div class="flex items-center space-x-4">
                    <img src="https://eu.ui-avatars.com/api/?name=<?= $follower['username'] ?>" alt="user_avatar" class="w-9 h-9 rounded-lg flex-none">
                    <a href="/<?= $follower['username'] ?>" class="hover:text-lime-500">@<?= $follower['username'] ?></a>
                </div>
            </div>
        <?php endforeach ?>
    <?php else : ?>
        <div>
            <p class="text-zinc-300">Questo utente non ha followers.</p>
        </div>
    <?php endif ?>
</div>