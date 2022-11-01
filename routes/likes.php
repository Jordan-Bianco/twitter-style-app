<?php

use App\controllers\TweetLikeController;

$app->router->post('/like/{id}/add', [TweetLikeController::class, 'store']);
$app->router->post('/like/{id}/remove', [TweetLikeController::class, 'destroy']);
