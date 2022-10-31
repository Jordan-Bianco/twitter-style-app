<?php

use App\core\Application;

class m0004_comment
{
    public function up()
    {
        $db = Application::$app->db;

        $query = "
            CREATE TABLE comments (
                id int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tweet_id int UNSIGNED NOT NULL,
                user_id int UNSIGNED NOT NULL,
                body text NOT NULL,
                created_at timestamp DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (tweet_id) REFERENCES tweets(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=INNODB;
            ";

        $db->pdo->exec($query);
    }

    public function down()
    {
        $db = Application::$app->db;

        $query = "DROP TABLE comments";

        $db->pdo->exec($query);
    }
}