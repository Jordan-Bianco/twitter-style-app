<?php

namespace App\controllers;

use App\core\exceptions\NotFoundException;
use App\core\middlewares\AuthMiddleware;
use App\core\Request;
use App\models\Tweet;

class TweetController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new AuthMiddleware([
            'store',
            'edit',
            'update',
            'destroy'
        ]));
    }

    public function index()
    {
        $perPage = 5;
        $total = $this->app->builder->count('tweets');
        $totalPages = ceil($total / $perPage);
        $currentPage = isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1;

        $offset = ($currentPage - 1) * $perPage;
        $rowCount = $perPage;

        $tweets = $this->getTweets($offset, $rowCount);

        $mostLiked = $this->getMostLikedTweets();

        $mostCommented = $this->getMostCommentedTweets();

        $this->view('tweets/index', [
            'tweets' => $tweets,
            'mostLiked' => $mostLiked,
            'mostCommented' => $mostCommented,
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
        ]);
    }

    public function store(Request $request)
    {
        $rules = ['body' => ['required', 'max:300']];
        $validated = $request->validate($_POST, $rules, '/tweets');

        $tweet = new Tweet();
        $tweet->create([
            'user_id' => $this->app->session->get('user')['id'],
            'body' => $validated['body']
        ]);

        $this->app->response->redirect('/tweets');
    }

    public function show(Request $request)
    {
        $perPage = 3;
        $total = $this->app->builder->count('comments', ['tweet_id', $request->routeParams['id']]);
        $totalPages = ceil($total / $perPage);
        $currentPage = isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1;

        $offset = ($currentPage - 1) * $perPage;
        $rowCount = $perPage;

        $tweet = $this->app->builder
            ->select('tweets', [
                'tweets.*',
                'users.username',
                '(SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.id) as likes_count',
                '(SELECT COUNT(*) FROM comments WHERE comments.tweet_id = tweets.id) as comments_count'
            ])
            ->leftJoin('likes', 'tweet_id', 'tweets', 'id')
            ->leftJoin('comments', 'tweet_id', 'tweets', 'id')
            ->join('users', 'id', 'tweets', 'user_id')
            ->where('id', $request->routeParams['id'], '=', 'tweets.')
            ->first();

        if (is_null($tweet) || is_null($tweet['id'])) {
            throw new NotFoundException();
        }

        $comments = $this->app->builder->raw(
            "SELECT comments.*, users.username
            FROM comments
            INNER JOIN users
            ON users.id = comments.user_id 
            WHERE comments.tweet_id = :tweet_id
            ORDER BY comments.created_at DESC
            LIMIT $offset, $rowCount;
            ",
            [':tweet_id' => $tweet['id']]
        );

        $this->view('tweets/show', [
            'tweet' => $tweet,
            'comments' => $comments,
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
        ]);
    }

    public function edit(Request $request)
    {
        $tweet = $this->app->builder
            ->select('tweets')
            ->where('id', $request->routeParams['id'])
            ->first();

        if (!$tweet) {
            throw new NotFoundException();
        }

        if (!$this->isAuthorized($tweet['user_id'])) {
            return $this->app->response->redirect('/tweets');
        }

        $this->view('tweets/edit', ['tweet' => $tweet]);
    }

    public function update(Request $request)
    {
        $tweet = $this->app->builder
            ->select('tweets')
            ->where('id', $request->routeParams['id'])
            ->first();

        if (!$this->isAuthorized($tweet['user_id'])) {
            return $this->app->response->redirect('/tweets');
        }

        $rules = ['body' => ['required', 'max:300']];

        $validated = $request->validate($_POST, $rules, '/tweets/' . $tweet['id'] . '/edit');

        $this->app->builder
            ->update('tweets', $validated, $tweet['id']);

        $this->app->response->redirect('/tweets/' . $tweet['id'] . '/edit')
            ->with('success', 'Tweet updated');
    }

    public function destroy(Request $request)
    {
        $tweet = $this->app->builder
            ->select('tweets')
            ->where('id', $request->routeParams['id'])
            ->first();

        if (!$this->isAuthorized($tweet['user_id'])) {
            return $this->app->response->redirect('/tweets');
        }

        $this->app->builder->delete('tweets', 'id', $tweet['id']);

        if (!str_contains($_SERVER['HTTP_REFERER'], 'tweets')) {
            $this->app->response->redirect($_SERVER['HTTP_REFERER'])
                ->with('success', 'Tweet deleted');
            return;
        }

        $this->app->response->redirect('/tweets')
            ->with('success', 'Tweet eliminato.');
    }

    /**
     * @param int $offset
     * @param int $rowCount
     * @return array
     */
    public function getTweets(int $offset, int $rowCount): array
    {
        return $this->app->builder
            ->select('tweets', [
                'tweets.*',
                'users.username',
                '(SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.id) as likes_count',
                '(SELECT COUNT(*) FROM comments WHERE comments.tweet_id = tweets.id) as comments_count'
            ])
            ->join('users', 'id', 'tweets', 'user_id')
            ->leftJoin('likes', 'tweet_id', 'tweets', 'id')
            ->leftJoin('comments', 'tweet_id', 'tweets', 'id')
            ->groupBy('tweets.id')
            ->latest('tweets')
            ->limit($offset, $rowCount)
            ->get();
    }

    /**
     * @return array 
     */
    public function getMostLikedTweets(): array
    {
        return $this->app->builder
            ->select('tweets', [
                '(SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.id) as likes_count',
                '(SELECT COUNT(*) FROM comments WHERE comments.tweet_id = tweets.id) as comments_count',
                'tweets.id',
                'tweets.body',
                'tweets.created_at',
                'users.username'
            ])
            ->join('likes', 'tweet_id', 'tweets', 'id')
            ->join('users', 'id', 'tweets', 'user_id')
            ->leftJoin('comments', 'tweet_id', 'tweets', 'id')
            ->groupBy('tweets.id')
            ->orderBy('likes_count', 'desc')
            ->limit(0, 2)
            ->get();
    }

    /**
     * @return array 
     */
    public function getMostCommentedTweets(): array
    {
        return $this->app->builder
            ->select('tweets', [
                '(SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.id) as likes_count',
                '(SELECT COUNT(*) FROM comments WHERE comments.tweet_id = tweets.id) as comments_count',
                'tweets.id',
                'tweets.body',
                'tweets.created_at',
                'users.username'
            ])
            ->join('users', 'id', 'tweets', 'user_id')
            ->leftjoin('likes', 'tweet_id', 'tweets', 'id')
            ->leftJoin('comments', 'tweet_id', 'tweets', 'id')
            ->groupBy('tweets.id')
            ->orderBy('comments_count', 'desc')
            ->limit(0, 2)
            ->get();
    }
}
