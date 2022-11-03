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
$usernames = ['user1', 'johnDoe', 'janeDoe'];
$emails = ['user@mail.com', 'johnDoe@mail.com', 'janeDoe@mail.com'];

for ($i = 0; $i < 3; $i++) {
    $password = password_hash('password', PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));

    $queries[] = "INSERT INTO users(username, email, password, verified, token) VALUES('$usernames[$i]', '$emails[$i]', '$password', 1, '$token');";
}

/** Tweets */
$userIds = ["1", "2", "3"];

for ($i = 0; $i < 15; $i++) {
    $k = array_rand($userIds);
    $userId = $userIds[$k];

    $body = getBody();

    $queries[] = "INSERT INTO tweets(user_id, body) VALUES('$userId', '$body');";
}

/** Comments */
$userIds = ['1', '2', '3'];
$tweetIds = [];

for ($i = 1; $i <= 15; $i++) {
    $tweetIds[] = "$i";
}

for ($i = 0; $i < 12; $i++) {
    $uk = array_rand($userIds);
    $userId = $userIds[$uk];

    $tk = array_rand($tweetIds);
    $tweetId = $tweetIds[$tk];

    $body = getBody();

    $queries[] = "INSERT INTO comments(tweet_id, user_id, body) VALUES('$tweetId', '$userId', '$body');";
}

/** Like */
foreach ($userIds as $userId) {
    for ($i = 0; $i < 3; $i++) {
        $queries[] = "INSERT INTO likes(tweet_id, user_id) VALUES('$tweetIds[$i]', '$userId');";
    }
}

/** Follow */
$queries[] = "INSERT INTO follows(follower_id, following_id, status) VALUES('2', '1', 'Pending');";
$queries[] = "INSERT INTO follows(follower_id, following_id, status) VALUES('3', '1', 'Pending');";

echo 'seeding data...' . PHP_EOL;

foreach ($queries as $query) {
    $app->db->pdo->exec($query);
}

echo 'seeding completed';

/**
 * Create body for tweets and comments
 * @return string
 */
function getBody(): string
{
    $words = ['lorem', 'ipsum', 'dolor', 'sit', 'amet', 'veniam', 'fuga', 'alias', 'consectetur', 'adipisicing', 'elit', 'misi', 'eveniet'];
    $body = '';

    for ($i = 1; $i < 20; $i++) {
        $body .= $words[rand(0, count($words) - 1)] . ' ';
    }

    return ucfirst($body);
}
