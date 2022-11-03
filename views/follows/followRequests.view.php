<?php

?>

<div class="text-xs">
    <div class="flex items-center space-x-2 mb-4">
        <svg class="w-[18px] h-[18px] text-sky-500" fill=" currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
        </svg>
        <h4 class="font-medium text-sm">Recent requests</h4>
    </div>
    <?php foreach ($followRequests as $request) : ?>
        <div class="panel mb-3">
            <p class="mb-2"><a href="/<?= $request['username'] ?>" class="text-sky-500 font-medium"><?= $request['username'] ?></a> requested to follow you</p>
            <span class="block text-zinc-500"><?= $request['created_at'] ?></span>

            <!-- Accept or Decline -->
            <div class="flex items-center space-x-2 mt-4">
                <form action="/followers/<?= $request['id'] ?>/accept" method="post">
                    <footer class="flex justify-end">
                        <button class="border border-zinc-700 hover:bg-zinc-700 transition px-2 py-1.5 rounded-lg" type="submit">
                            Accept
                        </button>
                    </footer>
                </form>
                <form action="/followers/<?= $request['id'] ?>/decline" method="post">
                    <footer class="flex justify-end">
                        <button class="border border-zinc-700 hover:bg-zinc-700 transition px-2 py-1.5 rounded-lg" type="submit">
                            Decline
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    <?php endforeach ?>

    <?php if (count($followRequests) > 0) : ?>
        <a href="/<?= $user['username'] ?>/followers" class="text-zinc-500 hover:underline transition">View all</a>
    <?php endif ?>
</div>