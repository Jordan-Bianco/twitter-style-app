<?php

use App\core\Application;

class m0002_tweet
{
    public function up()
    {
        $db = Application::$app->db;

        $query = "
            CREATE TABLE tweets (
                id          int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id     int UNSIGNED NOT NULL,
                body        text NOT NULL,
                created_at  timestamp DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=INNODB;
            ";

        $db->pdo->exec($query);
    }

    public function down()
    {
        $db = Application::$app->db;

        $query = "DROP TABLE tweets";

        $db->pdo->exec($query);
    }
}