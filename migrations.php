<?php

use App\core\Application;
use App\core\Config;

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$app = new Application(new Config($_ENV));

if (isset($argv[1]) && $argv[1] === 'truncate') {
    $app->db->truncateDatabaseTables();
    exit;
}

if (isset($argv[1]) && $argv[1] === 'drop') {
    $app->db->dropDatabaseTables();
    exit;
}

$app->db->applyMigrations();