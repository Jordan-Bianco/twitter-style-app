<?php

namespace App\controllers;

use App\core\middlewares\AuthMiddleware;
use App\core\Request;

class FollowController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new AuthMiddleware([
            'store',
            'destroy'
        ]));
    }

    public function store(Request $request)
    {
        $this->app->builder->insert('follows', [
            'follower_id' => $this->app->session->authId(),
            'following_id' => $request->routeParams['id']
        ]);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }

    public function destroy(Request $request)
    {
        $follow = $this->app->builder
            ->select('follows')
            ->where('follower_id', $this->app->session->authId())
            ->andWhere('following_id', $request->routeParams['id'])
            ->first();

        $this->app->builder->delete('follows', 'id', $follow['id']);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }
}
