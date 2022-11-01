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

    public function followersIndex(Request $request)
    {
        $followers = $this->app->builder
            ->select('follows', [
                'follows.*',
                'users.username'
            ])
            ->join('users', 'id', 'follows', 'follower_id')
            ->whereSubquery('following_id', '(SELECT id FROM users WHERE username = :username)', $request->routeParams['username'])
            ->get();

        $this->view('/users/follows/followers', ['followers' => $followers]);
    }

    public function followingIndex(Request $request)
    {
        /** Prendo dalla query string il parametro status, per filtrare le richieste di follow */
        $status = 'pending';

        if (isset($_GET['status'])) {
            $status = $_GET['status'];

            /** Se il parametro è diverso da uno di questi 3 status, setto status come pending  */
            if (!in_array($status, ['pending', 'accepted', 'declined'])) {
                $status = 'pending';
            }
        }

        /** Se mi trovo in una pagina diversa dall'utente loggato, mostro sempre e solo le richieste accettate, a prescindere dalla query string */
        // fix
        if ($request->routeParams['username'] !== $this->app->session->get('user')['username']) {
            $status = 'accepted';
        }

        $followings = $this->app->builder
            ->select('follows', [
                'follows.*',
                'users.username'
            ])
            ->join('users', 'id', 'follows', 'following_id')
            ->whereSubquery('follower_id', '(SELECT id FROM users WHERE username = :username)', $request->routeParams['username'])
            ->andWhere('status', $status)
            ->get();

        $this->view('/users/follows/following', [
            'followings' => $followings,
            'username' => $request->routeParams['username']
        ]);
    }

    public function store(Request $request)
    {
        $this->app->builder->insert('follows', [
            'follower_id' => $this->app->session->get('user')['id'],
            'following_id' => $request->routeParams['id']
        ]);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }

    public function destroy(Request $request)
    {
        $follow = $this->app->builder
            ->select('follows')
            ->where('follower_id', $this->app->session->get('user')['id'])
            ->andWhere('following_id', $request->routeParams['id'])
            ->first();

        $this->app->builder->delete('follows', 'id', $follow['id']);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }
}
