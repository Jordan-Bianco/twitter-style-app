<?php

namespace App\controllers;

use App\core\exceptions\NotFoundException;
use App\core\middlewares\AuthMiddleware;
use App\core\middlewares\SearchMiddleware;
use App\core\Request;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new AuthMiddleware(['show', 'search']));

        $this->registerMiddleware(new SearchMiddleware(['search']));
    }

    public function show(Request $request)
    {
        $user = $this->app->builder
            ->select([
                'users.id',
                'users.username',
                'users.bio',
                'users.email',
                'users.created_at',
                '(SELECT COUNT(*) FROM tweets WHERE user_id = users.id) as tweets_count',
                "(SELECT COUNT(*) FROM follows WHERE follower_id = users.id AND status = 'Accepted') as following_count",
                "(SELECT COUNT(*) FROM follows WHERE following_id = users.id AND status = 'Accepted') as followers_count"
            ])
            ->from('users')
            ->where('username', $request->routeParams['username'])
            ->first();

        if (!$user) {
            throw new NotFoundException();
        }

        $perPage = 5;
        $total = $this->app->builder
            ->count()
            ->from('tweets')
            ->where('user_id', $user['id'])
            ->getCount();

        $totalPages = ceil($total / $perPage);
        $currentPage = isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1;

        $offset = ($currentPage - 1) * $perPage;
        $rowCount = $perPage;

        $tweets = $this->app->builder->raw(
            "SELECT
                DISTINCT tweets.*,
                users.username,
                (SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.id) as likes_count,
                (SELECT COUNT(*) FROM comments WHERE comments.tweet_id = tweets.id) as comments_count
            FROM tweets
            INNER JOIN users
            ON users.id = tweets.user_id
            LEFT JOIN likes
            ON likes.tweet_id = tweets.id
            LEFT JOIN comments
            ON comments.tweet_id = tweets.id
            WHERE users.id = :user_id
            ORDER BY tweets.created_at DESC
            LIMIT $offset, $rowCount",
            [':user_id' => $user['id']]
        );

        $this->view('users/show', [
            'user' => $user,
            'tweets' => $tweets,
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
        ]);
    }

    public function search()
    {
        $search = stripslashes(trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS)));

        $users = $this->app->builder->search('users', ['username', 'email'], $search);

        $this->view('users/search', [
            'users' => $users,
        ]);
    }
}
