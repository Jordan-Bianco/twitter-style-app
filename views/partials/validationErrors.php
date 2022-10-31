<?php

use App\core\Application;

if (isset($_SESSION['validationErrors']) && !empty($_SESSION['validationErrors'])) : ?>
    <div class="bg-red-200 text-red-500 font-medium p-4 rounded-xl mb-4 text-xs">
        <?php foreach (Application::$app->session->getValidationErrors() as $error) : ?>
            <span class="block">
                <?= $error ?>
            </span>
        <?php endforeach ?>
        <?php Application::$app->session->removeValidationErrors() ?>
    </div>
<?php endif ?>