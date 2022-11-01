<?php

use App\controllers\UserController;

$app->router->get('/{username}', [UserController::class, 'show']);
$app->router->get('/users', [UserController::class, 'search']);
