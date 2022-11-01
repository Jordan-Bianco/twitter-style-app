<?php

use App\controllers\TweetCommentController;

$app->router->post('/tweets/{id}/comments', [TweetCommentController::class, 'store']);
$app->router->get('/comments/{id}/edit', [TweetCommentController::class, 'edit']);
$app->router->post('/comments/{id}/update', [TweetCommentController::class, 'update']);
$app->router->post('/comments/{id}/delete', [TweetCommentController::class, 'destroy']);
