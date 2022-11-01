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
        /** $request->routeParams['id'] è inteso come l'ID del record della richiesta di follow */
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
        /** Prendo dalla query string il parametro status, per filtrare le richieste di follow */
        $status = 'pending';

        if (isset($_GET['status'])) {
            $status = $_GET['status'];

            /** Se il parametro è diverso da uno di questi 3 status, setto status come pending  */
            if (!in_array($status, ['pending', 'accepted', 'declined'])) {
                $status = 'pending';
            }
        }

        /** Se non c'è un utente loggato, mostro solo le richiest di follow accettate */
        if (!$this->app->session->get('user')) {
            $status = 'accepted';
            /** Altrimenti se c'è un utente loggato, ma mi trovo in un profilo diverso da quello dell'utente loggato,
             * mostro sempre e solo le richieste accettate, a prescindere dalla query string
             **/
        } else if ($username !== $this->app->session->get('user')['username']) {
            $status = 'accepted';
        }

        return $status;
    }
}
