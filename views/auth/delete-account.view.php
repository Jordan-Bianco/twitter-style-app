<?php

/** @var $this \app\core\Renderer  */
$this->title .= ' - Delete account';
?>

<div class="max-w-[350px] mx-auto mt-10">
    <section class="p-6 border border-zinc-700 shadow-md shadow-zinc-900 rounded-lg">
        <h2 class="text-center text-3xl font-medium mb-2.5">Delete account</h2>
        <p class="text-center text-zinc-400 text-xs mb-6">Enter your email address to delete your account.</p>

        <!-- ValidationErrors -->
        <?php require_once ROOT_PATH . '/views/partials/validationErrors.php' ?>

        <form action="/delete-account" method="POST">
            <div class="mb-5">
                <label for="email" class="block mb-1 text-zinc-300 text-xs">
                    Email
                </label>
                <input name="email" placeholder="Please enter your email address" type="email" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <button type="submit" class="tracking-wide w-full bg-red-500 hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-300 text-white p-3 rounded-lg text-xs">
                Delete account
            </button>
        </form>
    </section>
</div>