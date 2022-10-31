<?php

namespace App\models;

class User extends Model
{
    public $fillables = [
        'username',
        'bio',
        'email',
        'password',
        'token'
    ];

    public function table(): string
    {
        return 'users';
    }
}
