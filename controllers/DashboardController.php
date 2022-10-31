<?php

namespace App\controllers;

use App\core\exceptions\ForbiddenException;
use App\core\middlewares\AuthMiddleware;
use App\core\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new AuthMiddleware(['show', 'update', 'destroy']));
    }

    public function show()
    {
        $this->view('users/settings');
    }

    public function update(Request $request)
    {
        $user = $this->app->session->get('user');

        $rules = [
            'username' => ['required', 'alpha_dash', 'unique:users-' . $user['id']],
            'bio'      => ['max:300']
        ];

        $validated = $request->validate($_POST, $rules, '/settings');

        $this->app->builder->update('users', $validated, $user['id']);

        $this->updateSessionUser($user['id']);

        $this->app->response->redirect('/settings')
            ->with('success', 'Hai aggiornato il tuo profilo!');
    }

    public function destroy(Request $request)
    {
        if ($request->isGet()) {
            return $this->view('auth/delete-account');
        }

        $validated = $request->validate($_POST, ['email' => ['required']], '/delete-account');

        if ($validated['email'] !== $this->app->session->get('user')['email']) {
            throw new ForbiddenException();
        }

        $this->app->builder->delete('users', 'id', $this->app->session->get('user')['id']);

        $this->app->session->remove('user');
        $this->app->session->destroySession();

        $this->app->response->redirect('/');
    }

    /**
     * @param int $userId
     * @return void
     */
    protected function updateSessionUser(int $userId): void
    {
        $user = $this->app->builder->select('users')->where('id', $userId)->first();

        $user = array_diff_key($user, array_flip(['password']));

        $this->app->session->set('user', $user);
    }
}
