<?php

namespace App\models;

use App\core\Application;

class Follow extends Model
{
    public function table(): string
    {
        return 'follows';
    }

    /**
     * @param int $userId
     * @return bool
     */
    public static function isFollowing(int $userId): bool
    {
        if (!Application::$app->session->isLoggedIn()) {
            return false;
        }

        $follow = Application::$app->builder
            ->select('follows')
            ->where('follower_id', Application::$app->session->get('user')['id'])
            ->andWhere('following_id', $userId)
            ->first();

        return is_null($follow) ? false : true;
    }
}
