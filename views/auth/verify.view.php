<?php

use App\core\Application;

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

$update = Application::$app->builder->update('users', ['verified' => true], $user['id']);

if ($update) {
    Application::$app->response->redirect('/login')
        ->with('success', 'Grazie per aver confermato la tua email.');
}
