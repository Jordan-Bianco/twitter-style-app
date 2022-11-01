<?php

use App\controllers\DashboardController;
use App\controllers\FollowController;
use App\controllers\TweetCommentController;
use App\controllers\TweetController;
use App\controllers\TweetLikeController;
use App\controllers\UpdatePasswordController;
use App\controllers\UserController;

require __DIR__ . '/auth.php';

/** Tweet routes */
$app->router->get('/', [TweetController::class, 'index']);
$app->router->get('/tweets', [TweetController::class, 'index']);
$app->router->post('/tweets', [TweetController::class, 'store']);
$app->router->get('tweets/{id}', [TweetController::class, 'show']);
$app->router->get('/tweets/{id}/edit', [TweetController::class, 'edit']);
$app->router->post('/tweets/{id}/update', [TweetController::class, 'update']);
$app->router->post('/tweets/{id}/delete', [TweetController::class, 'destroy']);

/** User routes */
$app->router->get('/{username}', [UserController::class, 'show']);
$app->router->get('/users', [UserController::class, 'search']);

/** Like routes */
$app->router->post('/like/{id}/add', [TweetLikeController::class, 'store']);
$app->router->post('/like/{id}/remove', [TweetLikeController::class, 'destroy']);

/** Comments route */
$app->router->post('/tweets/{id}/comments', [TweetCommentController::class, 'store']);
$app->router->get('/comments/{id}/edit', [TweetCommentController::class, 'edit']);
$app->router->post('/comments/{id}/update', [TweetCommentController::class, 'update']);
$app->router->post('/comments/{id}/delete', [TweetCommentController::class, 'destroy']);

/** Follows route */
$app->router->get('/{username}/followers', [FollowController::class, 'followersIndex']);
$app->router->get('/{username}/following', [FollowController::class, 'followingIndex']);
$app->router->post('/users/{id}/follow', [FollowController::class, 'store']);
$app->router->post('/users/{id}/unfollow', [FollowController::class, 'destroy']);

/** Dashboard */
$app->router->get('/settings', [DashboardController::class, 'show']);
$app->router->post('/settings', [DashboardController::class, 'update']);
$app->router->get('/delete-account', [DashboardController::class, 'destroy']);
$app->router->post('/delete-account', [DashboardController::class, 'destroy']);
$app->router->post('/update-password', [UpdatePasswordController::class, 'update']);
