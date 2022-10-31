<?php

namespace App\controllers;

use App\core\exceptions\NotFoundException;
use App\core\middlewares\AuthMiddleware;
use App\core\Request;
use App\models\Comment;

class TweetCommentController extends Controller
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

    public function edit(Request $request)
    {
        $comment = $this->app->builder
            ->select('comments')
            ->where('id', $request->routeParams['id'])
            ->first();

        if (!$comment) {
            throw new NotFoundException();
        }

        if (!$this->isAuthorized($comment['user_id'])) {
            return $this->app->response->redirect('/tweets');
        }

        $this->view('comments/edit', ['comment' => $comment]);
    }

    public function update(Request $request)
    {
        $comment = $this->app->builder
            ->select('comments')
            ->where('id', $request->routeParams['id'])
            ->first();

        if (!$this->isAuthorized($comment['user_id'])) {
            return $this->app->response->redirect('/tweets');
        }

        $rules = ['body' => ['required', 'max:300']];

        $validated = $request->validate($_POST, $rules, '/comments/' . $comment['id'] . '/edit');

        $this->app->builder
            ->update('comments', $validated, $comment['id']);

        $this->app->response->redirect('/comments/' . $comment['id'] . '/edit')
            ->with('success', 'Commento modificato.');
    }

    public function store(Request $request)
    {
        $rules = [
            'body' => ['required', 'max:300'],
        ];

        $validated = $request->validate($_POST, $rules, '/tweets/' . $request->routeParams['id']);

        $comment = new Comment();

        $comment->create([
            'user_id' => $this->app->session->get('user')['id'],
            'tweet_id' => $request->routeParams['id'],
            'body' => $validated['body']
        ]);

        $this->app->response->redirect("/tweets/" . $request->routeParams['id']);
    }

    public function destroy(Request $request)
    {
        $comment = $this->app->builder
            ->select('comments')
            ->where('id', $request->routeParams['id'])
            ->first();

        if (!$this->isAuthorized($comment['user_id'])) {
            return $this->app->response->redirect('/tweets');
        }

        $this->app->builder->delete('comments', 'id', $comment['id']);

        $this->app->response->redirect('/tweets/' . $comment['tweet_id'])
            ->with('success', 'Commento eliminato.');
    }
}
