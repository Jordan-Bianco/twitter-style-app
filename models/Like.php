<?php

namespace App\models;

use App\core\Application;

class Like extends Model
{
    public function table(): string
    {
        return 'likes';
    }

    /**
     * @param int $userId
     * @param int $resourceId
     * @return bool
     */
    public static function hasBeenLikedBy(int $userId, int $resourceId): bool
    {
        $likeInDb = Application::$app->builder
            ->select()
            ->from('likes')
            ->where('user_id', $userId)
            ->andWhere('tweet_id', $resourceId)
            ->first();

        return is_null($likeInDb) ? false : true;
    }
}
