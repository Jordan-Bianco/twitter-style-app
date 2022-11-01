<?php

use App\controllers\TweetController;

$app->router->get('/', [TweetController::class, 'index']);
$app->router->get('/tweets', [TweetController::class, 'index']);
$app->router->post('/tweets', [TweetController::class, 'store']);
$app->router->get('tweets/{id}', [TweetController::class, 'show']);
$app->router->get('/tweets/{id}/edit', [TweetController::class, 'edit']);
$app->router->post('/tweets/{id}/update', [TweetController::class, 'update']);
$app->router->post('/tweets/{id}/delete', [TweetController::class, 'destroy']);
