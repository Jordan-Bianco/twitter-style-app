<?php

use App\core\Application;
use App\core\Session;
?>

<div class="max-w-lg mx-auto">
    <?php if (Session::isLoggedIn() && Application::$app->session->get('user')['username'] === $username) : ?>
        <div class="border-b border-zinc-700 pb-4 mb-8 flex items-center justify-around">
            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'pending' || !isset($_GET['status']) ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/followers?status=pending">In attesa</a>

            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'accepted' ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/followers?status=accepted">Accettate</a>

            <a class="<?= isset($_GET['status']) && $_GET['status'] === 'declined' ? 'text-lime-500 font-medium' : 'text-zinc-500' ?> text-xs" href="/<?= $username ?>/followers?status=declined">Respinte</a>
        </div>
    <?php endif ?>

    <?php foreach ($followers as $follower) : ?>
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-4">
                <img src="https://eu.ui-avatars.com/api/?name=<?= $follower['username'] ?>" alt="user_avatar" class="w-8 h-8 rounded-lg flex-none">
                <div>
                    <a href="/<?= $follower['username'] ?>" class="hover:text-lime-500">@<?= $follower['username'] ?></a>
                </div>
            </div>

            <div class="space-x-1">
                <?php if (Session::isLoggedIn() && Application::$app->session->get('user')['id'] === $follower['following_id']) : ?>

                    <?php if ($follower['status'] !== 'Declined') : ?>
                        <!-- Se la richiesta è in stato Pending, posso accettare o declinare -->
                        <?php if ($follower['status'] === 'Pending') : ?>
                            <div class="flex items-center space-x-2">
                                <form action="/followers/<?= $follower['id'] ?>/accept" method="post">
                                    <footer class="flex justify-end">
                                        <button class="hover:text-lime-500" type="submit">
                                            Accetta
                                        </button>
                                    </footer>
                                </form>
                                <form action="/followers/<?= $follower['id'] ?>/decline" method="post">
                                    <footer class="flex justify-end">
                                        <button class="hover:text-red-500" type="submit">
                                            Rifiuta
                                        </button>
                                    </footer>
                                </form>
                            </div>
                        <?php elseif ($follower['status'] === 'Accepted') : ?>
                            <form action="/followers/<?= $follower['id'] ?>/remove" method="post">
                                <footer class="flex justify-end">
                                    <button class="hover:text-red-500" type="submit">
                                        Rimuovi
                                    </button>
                                </footer>
                            </form>
                        <?php endif ?>
                        <!-- Se è in stato Accepted, posso rimuovere il follow -->
                    <?php endif ?>

                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
</div>