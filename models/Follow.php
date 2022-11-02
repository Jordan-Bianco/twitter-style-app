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
     * @return bool|string
     */
    public static function requestStatus(int $userId): bool|string
    {
        $follow = Application::$app->builder
            ->select('follows')
            ->where('follower_id', Application::$app->session->get('user')['id'])
            ->andWhere('following_id', $userId)
            ->first();

        return is_null($follow) ? false : $follow['status'];
    }
}
