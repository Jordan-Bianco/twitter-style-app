<?php

use App\core\Application;

$queryString = $_SERVER['QUERY_STRING'] ?? false;
/** If the query string is not present, or the id and token parameters are not present, redirect home */
if (!$queryString || !isset($_GET['id']) || !isset($_GET['token'])) {
    Application::$app->response->redirect('/');
    return;
}

$params = explode('&', $queryString);

$id = substr($params[0], strpos($params[0], '=') + 1);
$token = substr($params[1], strpos($params[1], '=') + 1);

$user = Application::$app->builder
    ->select()
    ->from('users')
    ->where('id', $id)
    ->first();

/** If the token in the url does not match the token assigned to the user, redirect home */
if ($user['token'] !== $token) {
    Application::$app->response->redirect('/');
    return;
}

$update = Application::$app->builder->update('users', ['verified' => true], $user['id']);

if ($update) {
    Application::$app->response->redirect('/login')
        ->with('success', 'Thank you for confirming your email');
}
