<?php

/** @var $this \app\core\Renderer  */
$this->title .= ' - Error';
?>

<div class="bg-blue-200 border-blue-300 text-blue-500 p-4 rounded">
    Ops.. something went wrong!

    <span class="font-semibold">
        <?= $error->getCode() . ' - ' . $error->getMessage() ?>
    </span>
</div>