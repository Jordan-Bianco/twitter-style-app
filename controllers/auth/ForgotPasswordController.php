<?php

namespace App\controllers\auth;

use App\controllers\Controller;
use App\core\Auth;
use App\core\middlewares\GuestMiddleware;
use App\core\Request;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new GuestMiddleware(['show', 'forgot']));
    }


    public function show()
    {
        $this->view('auth/forgot-password');
    }

    public function forgot(Request $request)
    {
        $rules = [
            'email' => ['required', 'email', 'exists:users']
        ];

        $validated = $request->validate($_POST, $rules, '/forgot-password');

        $user = $this->app->builder
            ->select('users')
            ->where('email', $validated['email'])
            ->first();

        Auth::sendResetPasswordMail($user);

        $this->app->response->redirect('/forgot-password')
            ->with('success', 'An email has been sent to you to reset your password');
    }
}
