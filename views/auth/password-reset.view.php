<?php

use App\core\Application;

/** @var $this \app\core\Renderer  */
$this->title .= ' - Password reset';


$queryString = $_SERVER['QUERY_STRING'] ?? false;

/** Se la query string non è presente, o non sono presenti i parametri id e token, redirect home */
if (!$queryString || !isset($_GET['id']) || !isset($_GET['token'])) {
    Application::$app->response->redirect('/');
    return;
}

$params = explode('&', $queryString);

$id = substr($params[0], strpos($params[0], '=') + 1);
$token = substr($params[1], strpos($params[1], '=') + 1);

$user = Application::$app->builder
    ->select('users')
    ->where('id', $id)
    ->first();

/** Se il token nella url non corrisponde al token assegnato all'utente, redirect home */
if ($user['token'] !== $token) {
    Application::$app->response->redirect('/');
    return;
}
?>

<div class="max-w-[350px] mx-auto mt-10">
    <section class="p-6 border border-zinc-700 shadow-md shadow-zinc-900 rounded-lg">
        <h2 class="text-center text-3xl font-medium mb-6">Password reset</h2>

        <!-- ValidationErrors -->
        <?php require_once ROOT_PATH . '/views/partials/validationErrors.php' ?>

        <form action="/password-reset" method="POST">

            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="token" value="<?= $token ?>">

            <div class="mb-6">
                <label for="password" class="block mb-1 text-zinc-300 text-xs">
                    Password
                </label>
                <input name="password" placeholder="Inserisci la tua nuova password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <div class="mb-8">
                <label for="password_confirm" class="block mb-1 text-zinc-300 text-xs">
                    Conferma password
                </label>
                <input name="password_confirm" placeholder="Conferma la nuova password" type="password" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <button type="submit" class="tracking-wide w-full bg-lime-500 hover:bg-lime-400 focus:outline-none focus:ring-2 focus:ring-lime-300 text-white p-3 rounded-lg text-xs">
                Resetta password
            </button>
        </form>
    </section>
</div>