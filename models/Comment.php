<?php

namespace App\models;

class Comment extends Model
{
    public $fillables = [
        'user_id',
        'tweet_id',
        'body'
    ];

    public function table(): string
    {
        return 'comments';
    }
}