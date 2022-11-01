<?php

use App\controllers\DashboardController;
use App\controllers\UpdatePasswordController;

$app->router->get('/settings', [DashboardController::class, 'show']);
$app->router->post('/settings', [DashboardController::class, 'update']);
$app->router->get('/delete-account', [DashboardController::class, 'destroy']);
$app->router->post('/delete-account', [DashboardController::class, 'destroy']);

$app->router->post('/update-password', [UpdatePasswordController::class, 'update']);
