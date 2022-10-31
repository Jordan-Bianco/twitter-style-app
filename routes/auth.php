<?php

use App\controllers\auth\ForgotPasswordController;
use App\controllers\auth\LoginController;
use App\controllers\auth\RegisterController;
use App\controllers\auth\ResetPasswordController;

$app->router->get('/login', [LoginController::class, 'show']);
$app->router->post('/login', [LoginController::class, 'login']);
$app->router->post('/logout', [LoginController::class, 'logout']);

$app->router->get('/register', [RegisterController::class, 'show']);
$app->router->post('/register', [RegisterController::class, 'register']);

$app->router->get('/verify', 'auth/verify');

$app->router->get('/forgot-password', [ForgotPasswordController::class, 'show']);
$app->router->post('/forgot-password', [ForgotPasswordController::class, 'forgot']);

$app->router->get('/password-reset', [ResetPasswordController::class, 'show']);
$app->router->post('/password-reset', [ResetPasswordController::class, 'reset']);
