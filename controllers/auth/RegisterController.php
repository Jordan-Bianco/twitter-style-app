<?php

namespace App\controllers\auth;

use App\controllers\Controller;
use App\core\Auth;
use App\core\middlewares\GuestMiddleware;
use App\core\Request;
use App\models\User;

class RegisterController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new GuestMiddleware(['show', 'register']));
    }


    public function show()
    {
        $this->view('auth/register');
    }

    /**
     * @param Request $request
     */
    public function register(Request $request)
    {
        $rules = [
            'username' => ['required', 'alpha_dash', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'number', 'special_char'],
            'password_confirm' => ['required', 'match:password'],
        ];

        $validated = $request->validate($_POST, $rules, '/register');

        $user = new User();

        $user->create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Auth::hash($validated['password']),
            'token' => Auth::generateToken()
        ]);

        $user = $this->app->builder
            ->select()
            ->from('users')
            ->where('email', $validated['email'])
            ->first();

        Auth::sendVerificationMail($user);

        $this->app->response->redirect('/login')
            ->with('success', 'Thank you for registering. An email has been sent to your address ' . $user['email'] . ' to verify your account.');
    }
}
