<?php

use App\core\Application;
use App\models\Like;

$user = Application::$app->session->get('user');

/** @var $this \app\core\Renderer  */
$this->title .= ' - Home';
?>

<div class="md:flex md:items-start md:space-x-8 space-y-6 md:space-y-0">

    <!-- Sidemenu -->
    <div class="md:w-1/4 space-y-8">
        <div class="panel">
            <div class="flex items-center space-x-3">
                <img src="https://eu.ui-avatars.com/api/?name=<?= $user['username'] ?>" alt="user_avatar" class="w-9 h-9 rounded-lg flex-none">

                <div>
                    <span class="block font-medium text-lime-500"><?= $user['username'] ?></span>
                    <span class="block text-xs text-zinc-500"><?= $user['email'] ?></span>
                </div>
            </div>

            <div class="mt-6 text-xs space-y-2">
                <a class="block" href="/<?= $user['username'] ?>">
                    Dashboard
                </a>
                <form action="/logout" method="POST">
                    <button type=" submit">Logout</button>
                </form>
            </div>
        </div>

        <!-- Follow Requests -->
        <?php require_once ROOT_PATH . '/views/follows/followRequests.view.php' ?>

    </div>

    <div class="md:w-2/4">
        <!-- Form create tweet -->
        <?php require_once ROOT_PATH . '/views/tweets/create.view.php' ?>

        <!-- Tweets list -->
        <?php require_once ROOT_PATH . '/views/tweets/list.view.php' ?>
    </div>

    <div class="md:w-1/4 space-y-8">
        <!-- Top rated tweets -->
        <?php require_once ROOT_PATH . '/views/tweets/topRated.view.php' ?>

        <!-- Most commented tweets -->
        <?php require_once ROOT_PATH . '/views/tweets/mostCommented.view.php' ?>
    </div>
</div>