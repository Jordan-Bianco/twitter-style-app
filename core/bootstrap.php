<?php

namespace App\core;

$app = new Application(new Config($_ENV));

require ROOT_PATH . '/routes/web.php';

$app->run();
