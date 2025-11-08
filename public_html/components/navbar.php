<?php
/**
 * Navigation Bar Component
 * @param array $config Site configuration
 * @param string $current_page Current page identifier
 */
?>
<header class="sticky top-0 z-50 flex h-16 w-full items-center justify-center bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
    <div class="flex w-full max-w-5xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <!-- Logo & Name -->
        <div class="flex items-center gap-3">
            <div class="flex size-6 items-center justify-center rounded-md bg-primary text-white">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white"><?= htmlspecialchars(
                $config["site"]["name"],
            ) ?></h2>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden items-center gap-8 md:flex" role="navigation" aria-label="Main navigation">
            <?php foreach ($config["navigation"] as $item): ?>
                <?php $is_active =
                    ltrim($item["url"], "/") === $current_page; ?>
                <?php if ($item["type"] === "button"): ?>
                    <a class="flex h-9 cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-primary px-4 text-sm font-bold text-white transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark <?= $is_active
                        ? "ring-2 ring-offset-2"
                        : "" ?>"
                           href="<?= htmlspecialchars($item["url"]) ?>"
                           aria-current="<?= $is_active ? "page" : "false" ?>">
                        <span class="truncate"><?= htmlspecialchars(
                            $item["label"],
                        ) ?></span>
                    </a>
                <?php else: ?>
                    <a class="text-sm font-medium transition-colors hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark
                               <?= $is_active
                                   ? "text-primary"
                                   : "text-gray-600 dark:text-gray-300" ?>"
                           href="<?= htmlspecialchars($item["url"]) ?>"
                           aria-current="<?= $is_active ? "page" : "false" ?>">
                        <?= htmlspecialchars($item["label"]) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>

        <!-- Mobile Menu Button -->
        <button class="md:hidden rounded-md p-2 text-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary"
                aria-label="Toggle navigation menu"
                id="mobile-menu-toggle">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </div>
</header>
