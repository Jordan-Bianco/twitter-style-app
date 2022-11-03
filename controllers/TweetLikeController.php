<?php

namespace App\controllers;

use App\core\exceptions\NotFoundException;
use App\core\middlewares\AuthMiddleware;
use App\core\Request;
use App\models\Tweet;

class TweetLikeController extends Controller
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
        $tweetId = $request->routeParams['id'];

        $this->app->builder->insert('likes', [
            'tweet_id' => $tweetId,
            'user_id' => $this->app->session->authId(),
        ]);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }

    public function destroy(Request $request)
    {
        $tweetId = $request->routeParams['id'];

        $likeInDb = $this->app->builder
            ->select('likes')
            ->where('user_id', $this->app->session->authId())
            ->andWhere('tweet_id', $tweetId)
            ->first();

        $this->app->builder->delete('likes', 'id', $likeInDb['id']);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }
}
