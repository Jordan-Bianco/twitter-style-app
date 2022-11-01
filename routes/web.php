<?php

use App\controllers\FollowController;
use App\controllers\FollowerController;
use App\controllers\FollowingController;

require __DIR__ . '/auth.php';
require __DIR__ . '/tweets.php';
require __DIR__ . '/comments.php';
require __DIR__ . '/likes.php';
require __DIR__ . '/user.php';
require __DIR__ . '/dashboard.php';


/** Follows route */
$app->router->post('/users/{id}/follow', [FollowController::class, 'store']);
$app->router->post('/users/{id}/unfollow', [FollowController::class, 'destroy']);

/** Followers */
$app->router->get('/{username}/followers', [FollowerController::class, 'index']);
$app->router->post('/followers/{id}/accept', [FollowerController::class, 'accept']);
$app->router->post('/followers/{id}/decline', [FollowerController::class, 'decline']);
$app->router->post('/followers/{id}/remove', [FollowerController::class, 'destroy']);

/** Following */
$app->router->get('/{username}/following', [FollowingController::class, 'index']);
$app->router->post('/following/{id}/remove', [FollowingController::class, 'destroy']);
