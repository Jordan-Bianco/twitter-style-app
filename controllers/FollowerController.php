<?php

namespace App\controllers;

use App\core\middlewares\AuthMiddleware;
use App\core\Request;

class FollowerController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new AuthMiddleware(['destroy']));
    }

    public function index(Request $request)
    {
        $status = $this->setFollowFilterStatus($request->routeParams['username']);

        $followers = $this->app->builder
            ->select('follows', [
                'follows.*',
                'users.username'
            ])
            ->join('users', 'id', 'follows', 'follower_id')
            ->whereSubquery('following_id', '(SELECT id FROM users WHERE username = :username)', $request->routeParams['username'])
            ->andWhere('status', $status)
            ->get();

        $this->view('/users/follows/followers', [
            'followers' => $followers,
            'username' => $request->routeParams['username']
        ]);
    }

    public function accept(Request $request)
    {
        /** $request->routeParams['id'] it is intended as the ID of the follow request record */
        $this->app->builder->update('follows', ['status' => 'Accepted'], $request->routeParams['id']);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }

    public function decline(Request $request)
    {
        $this->app->builder->update('follows', ['status' => 'Declined'], $request->routeParams['id']);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }

    public function destroy(Request $request)
    {
        $this->app->builder->delete('follows', 'id', $request->routeParams['id']);

        $this->app->response->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @param string $username
     * @return string 
     */
    protected function setFollowFilterStatus(string $username): string
    {
        /** Take the status parameter from the query string, to filter the follow requests */
        $status = 'pending';

        if (isset($_GET['status'])) {
            $status = $_GET['status'];

            /** If the parameter is different from one of these 3 statuses, set status as pending  */
            if (!in_array($status, ['pending', 'accepted', 'declined'])) {
                $status = 'pending';
            }
        }

        /** If there is no user logged in, only show accepted follow requests */
        if (!$this->app->session->get('user')) {
            $status = 'accepted';
            /** If there is a logged in user, but I am in a different profile from that of the logged in user, I always show only the accepted requests, regardless of the query string */
        } else if ($username !== $this->app->session->get('user')['username']) {
            $status = 'accepted';
        }

        return $status;
    }
}
