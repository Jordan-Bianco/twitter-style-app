<?php

namespace App\controllers\auth;

use App\controllers\Controller;
use App\core\Auth;
use App\core\middlewares\AuthMiddleware;
use App\core\middlewares\GuestMiddleware;
use App\core\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new GuestMiddleware(['show', 'login']));
        $this->registerMiddleware(new AuthMiddleware(['logout']));
    }

    public function show()
    {
        $this->view('auth/login');
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];

        $validated = $request->validate($_POST, $rules, '/login');

        $user = Auth::attemptLogin($validated['email'], $validated['password']);

        if (!$user) {
            $this->app->response->redirect('/login')
                ->withValidationErrors(['email' => 'Credenziali non corrette.'])
                ->withOldData($validated);

            return;
        }

        if (!Auth::isVerified($user)) {
            $this->app->response->redirect('/login')
                ->withValidationErrors(['email' => 'Sembra che tu non abbia verificato il tuo account. Verifica il tuo account per accedere.'])
                ->withOldData($validated);

            return;
        }

        $this->app->session->set('user', $user);

        $this->app->response->redirect('/')
            ->with('success', "Bentornato <strong> " . $user['username'] . "</strong>");
    }

    public function logout()
    {
        $this->app->session->destroySession();

        $this->app->response->redirect('/login')
            ->with('success', 'Ci vediamo!');
    }
}
