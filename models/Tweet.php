<?php

namespace App\models;

class Tweet extends Model
{
    public $fillables = [
        'user_id',
        'body'
    ];

    public function table(): string
    {
        return 'tweets';
    }
}