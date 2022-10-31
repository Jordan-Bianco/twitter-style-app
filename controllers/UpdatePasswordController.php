<?php

namespace App\controllers;

use App\core\Auth;
use App\core\exceptions\ForbiddenException;
use App\core\middlewares\AuthMiddleware;
use App\core\Request;

class UpdatePasswordController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new AuthMiddleware(['update']));
    }

    public function update(Request $request)
    {
        $rules = [
            'current_password' => ['required'],
            'new_password'     => ['required', 'min:8', 'number', 'special_char'],
            'password_confirm' => ['required', 'match:new_password'],
        ];

        $validated = $request->validate($_POST, $rules, '/settings');

        $id = $this->app->session->get('user')['id'];
        $user = $this->app->builder
            ->select('users')
            ->where('id', $id)
            ->first();

        if (!password_verify($validated['current_password'], $user['password'])) {
            return $this->app->response->redirect('/settings')
                ->withValidationErrors(['current_password' => 'La tua attuale password non coincide con quella indicata.']);
        }

        $password = Auth::hash($validated['new_password']);

        $this->app->builder->update('users', ['password' => $password], $user['id']);

        return $this->app->response->redirect('/settings')
            ->with('success', 'Hai aggiornato la tua password!');
    }
}
