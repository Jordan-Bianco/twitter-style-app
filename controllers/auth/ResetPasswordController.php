<?php

namespace App\controllers\auth;

use App\controllers\Controller;
use App\core\Auth;
use App\core\exceptions\ForbiddenException;
use App\core\middlewares\GuestMiddleware;
use App\core\Request;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new GuestMiddleware(['show', 'reset']));
    }


    public function show()
    {
        $this->view('auth/password-reset');
    }

    public function reset(Request $request)
    {
        $rules = [
            'password'         => ['required', 'min:8', 'number', 'special_char'],
            'password_confirm' => ['required', 'match:password'],
        ];

        $url = "/password-reset?id=" . $_POST['id'] . "&token=" . $_POST['token'];

        $validated = $request->validate($_POST, $rules, $url);

        $user = $this->app->builder
            ->select('users')
            ->where('id', $_POST['id'])
            ->first();

        if ($user['token'] !== $_POST['token']) {
            throw new ForbiddenException();
        }

        $this->app->builder
            ->update(
                'users',
                [
                    'password' => Auth::hash($validated['password'])
                ],
                $user['id']
            );

        $this->app->response->redirect('/login')
            ->with('success', 'Password reset successfully');
    }
}
