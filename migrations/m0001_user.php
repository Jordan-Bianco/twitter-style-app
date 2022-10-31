<?php

use App\core\Application;

class m0001_user
{
    public function up()
    {
        $db = Application::$app->db;

        $query = "
            CREATE TABLE users (
                id         int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username   varchar(255) unique NOT NULL,
                email      varchar(255) unique NOT NULL,
                bio        TINYTEXT NULL,
                password   varchar(255) NOT NULL,
                verified   boolean DEFAULT false,
                token      varchar(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
            ";

        $db->pdo->exec($query);
    }

    public function down()
    {
        $db = Application::$app->db;

        $query = "DROP TABLE users";

        $db->pdo->exec($query);
    }
}
