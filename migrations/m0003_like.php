<?php

use App\core\Application;

class m0003_like
{
    public function up()
    {
        $db = Application::$app->db;

        $query = "
            CREATE TABLE likes (
                id int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tweet_id int UNSIGNED NOT NULL,
                user_id int UNSIGNED NOT NULL,
                FOREIGN KEY (tweet_id) REFERENCES tweets(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=INNODB;
            ";

        $db->pdo->exec($query);
    }

    public function down()
    {
        $db = Application::$app->db;

        $query = "DROP TABLE likes";

        $db->pdo->exec($query);
    }
}