<?php

/** @var $this \app\core\Renderer  */
$this->title .= ' - Forgot password';
?>

<div class="max-w-[350px] mx-auto mt-10">
    <section class="p-6 border border-zinc-700 shadow-md shadow-zinc-900 rounded-lg">
        <h2 class="text-center text-3xl font-medium mb-2.5">Password recovery</h2>
        <p class="text-center text-zinc-400 text-xs mb-6">Enter your email address, an email will be sent to you containing a link to reset your password.</p>

        <!-- ValidationErrors -->
        <?php require_once ROOT_PATH . '/views/partials/validationErrors.php' ?>

        <form action="/forgot-password" method="POST">
            <div class="mb-5">
                <label for="email" class="block mb-1 text-zinc-300 text-xs">
                    Email
                </label>
                <input name="email" placeholder="Please enter your email address" type="email" class="w-full text-xs px-4 py-3 bg-zinc-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-700 transition">
            </div>

            <button type="submit" class="tracking-wide w-full bg-lime-500 hover:bg-lime-400 focus:outline-none focus:ring-2 focus:ring-lime-300 text-white p-3 rounded-lg text-xs">
                Send email
            </button>
        </form>
    </section>
</div>