<?php

use App\controllers\FollowController;
use App\controllers\FollowerController;
use App\controllers\FollowingController;

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
