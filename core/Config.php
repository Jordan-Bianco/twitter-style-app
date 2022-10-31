<?php

namespace App\core;

class Config
{
    public array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'database' => [
                'dsn' => $env['DB_DSN'],
                'user' => $env['DB_USER'],
                'password' => $env['DB_PASSWORD'],
            ],
            'site' => [
                'name' => $env['SITE_NAME'],
            ]
        ];
    }
}