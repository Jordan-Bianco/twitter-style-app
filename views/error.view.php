<?php

/** @var $this \app\core\Renderer  */
$this->title .= ' - Error';
?>

<div class="bg-sky-200 border-sky-300 text-sky-500 p-4 rounded">
    Ops.. something went wrong!

    <span class="font-semibold">
        <?= $error->getCode() . ' - ' . $error->getMessage() ?>
    </span>
</div>