<div class="max-w-lg mx-auto">
    <?php if (count($users) >= 1) : ?>

        <span class="block mb-6 text-zinc-500">
            <?= count($users) ?> search results for "<?= $_GET['search'] ?>"
        </span>

        <ul>
            <?php foreach ($users as $user) : ?>
                <li class="mb-3 panel">
                    <a href="/<?= $user['username'] ?>" class="flex items-center space-x-4">
                        <img src="https://eu.ui-avatars.com/api/?name=<?= $user['username'] ?>" alt="user_avatar" class="w-9 h-9 rounded-lg flex-none">
                        <div>
                            <span class="font-medium block text-sm hover:text-sky-500">@<?= $user['username'] ?></span>
                            <span class="block text-xs text-zinc-400"><?= $user['email'] ?></span>
                        </div>
                    </a>
                </li>

            <?php endforeach ?>
        </ul>
    <?php else : ?>
        No results for "<?= $_GET['search'] ?>"
    <?php endif ?>
</div>