<?php

use App\core\Application;
use App\core\Config;

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$app = new Application(new Config($_ENV));

if (isset($argv[1]) && $argv[1] === 'fresh') {
    $app->db->truncateDatabaseTables();
}

$queries = [];

/** Users */
$usernames = ['user1', 'sapirazyly', 'jedul', 'gyrogoha'];
$emails = ['user@mail.com', 'sapirazyly@mail.com', 'jedul@mail.com', 'gyrogoha@mail.com'];

for ($i = 0; $i < 4; $i++) {
    $password = password_hash('password', PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));

    $queries[] = "INSERT INTO users(username, email, password, verified, token) VALUES('$usernames[$i]', '$emails[$i]', '$password', 1, '$token');";
}

/** Tweets */
$userIds = ["1", "2", "3", "4"];
$words = [
    "Adipisicing elit. Nisi eveniet odit tempora laborum. Nulla reiciendis, consequuntur soluta, fuga repudiandae error.",
    "Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi eveniet",
    "Sit amet consectetur ingadipisicing elit. Nisi eveniet odit tempora laborum. Nulla reiciendis, consequuntur soluta, fuga repudiandae error.",
    "Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi eveniet odit tempora laborum. Nulla reiciendis, consequuntur soluta, fuga repudiandae error id incidunt rerum explicabo."
];

for ($i = 0; $i < 20; $i++) {
    $k = array_rand($userIds);
    $userId = $userIds[$k];

    $queries[] = "INSERT INTO tweets(user_id, body) VALUES($userId, '" . $words[array_rand($words)] . "');";
}

/** Comments */
$userIds = ['1', '2', '3', '4'];
$tweetIds = [];

for ($i = 1; $i <= 20; $i++) {
    $tweetIds[] = "$i";
}

for ($i = 0; $i < 50; $i++) {
    $uk = array_rand($userIds);
    $userId = $userIds[$uk];

    $tk = array_rand($tweetIds);
    $tweetId = $tweetIds[$tk];

    $queries[] = "INSERT INTO comments(tweet_id, user_id, body) VALUES('$tweetId', '$userId', '" . $words[array_rand($words)] . "');";
}

/** Like */
foreach ($userIds as $userId) {
    for ($i = 0; $i < 10; $i++) {
        $queries[] = "INSERT INTO likes(tweet_id, user_id) VALUES('$tweetIds[$i]', '$userId');";
    }
}

echo 'seeding data...' . PHP_EOL;

foreach ($queries as $query) {
    $app->db->pdo->exec($query);
}

echo 'seeding completed';
