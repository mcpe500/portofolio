<?php
/**
 * 404 Error Page
 */
http_response_code(404); ?>
<main class="flex flex-1 items-center justify-center">
    <div class="flex w-full max-w-5xl flex-col items-center px-4 py-16 text-center sm:px-6 lg:px-8">
        <h1 class="text-6xl font-black text-gray-900 dark:text-white mb-4">404</h1>
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-4">Page Not Found</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8">The page you're looking for doesn't exist.</p>
        <a href="/" class="flex h-12 items-center justify-center rounded-lg bg-primary px-6 text-base font-bold text-white transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark">
            Go Home
        </a>
    </div>
</main>
