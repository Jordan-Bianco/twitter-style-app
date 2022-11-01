<?php

use App\core\Application;

class m0005_follow
{
    public function up()
    {
        $db = Application::$app->db;

        $query = "
            CREATE TABLE follows (
                id int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                follower_id int UNSIGNED NOT NULL,
                following_id int UNSIGNED NOT NULL,
                status enum('Pending','Accepted', 'Declined') DEFAULT 'Pending',
                created_at timestamp DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=INNODB;
            ";

        $db->pdo->exec($query);
    }

    public function down()
    {
        $db = Application::$app->db;

        $query = "DROP TABLE follows";

        $db->pdo->exec($query);
    }
}