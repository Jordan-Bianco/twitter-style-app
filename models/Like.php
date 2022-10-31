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
     * @param int|null $userId
     * @param int $resourceId
     * @return bool
     */
    public static function hasBeenLikedBy(?int $userId, int $resourceId): bool
    {
        if (is_null($userId)) {
            return false;
        }

        $likeInDb = Application::$app->builder
            ->select('likes')
            ->where('user_id', $userId)
            ->andWhere('tweet_id', $resourceId)
            ->first();

        return is_null($likeInDb) ? false : true;
    }

}