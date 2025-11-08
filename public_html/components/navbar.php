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
        <a href="/" class="flex items-center gap-3 group">
            <div class="flex size-6 items-center justify-center rounded-md bg-primary text-white group-hover:bg-primary/90 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors"><?= htmlspecialchars(
                $config["site"]["name"] ?? "Site Name",
            ) ?></h2>
        </a>

        <!-- Desktop Navigation (hidden on mobile) -->
        <nav class="hidden items-center gap-8 md:flex" role="navigation" aria-label="Main navigation">
            <?php foreach ($config["navigation"] ?? [] as $item): ?>
                <?php
                $path = parse_url($item["url"] ?? "#", PHP_URL_PATH);
                $is_active = trim($path, "/") === ($current_page ?? "");
                $type = $item["type"] ?? "link";
                ?>

                <?php if ($type === "button"): ?>
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

        <!-- Mobile Menu Button (hidden on desktop) -->
        <button class="md:hidden rounded-md p-2 text-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary"
                aria-label="Toggle navigation menu"
                aria-expanded="false"
                id="mobile-menu-toggle">
            <span class="material-symbols-outlined" id="menu-icon">menu</span>
        </button>
    </div>

    <!-- Mobile Menu Dropdown (hidden by default) -->
    <div id="mobile-menu" class="hidden md:hidden absolute top-16 left-0 w-full bg-white dark:bg-gray-900 shadow-lg border-t border-gray-200 dark:border-gray-700">
        <nav class="flex flex-col p-4 gap-2" role="navigation" aria-label="Mobile navigation">
            <?php foreach ($config["navigation"] ?? [] as $item): ?>
                <?php
                $path = parse_url($item["url"] ?? "#", PHP_URL_PATH);
                $is_active = trim($path, "/") === ($current_page ?? "");
                $type = $item["type"] ?? "link";
                ?>

                <?php if ($type === "button"): ?>
                    <a class="flex h-9 items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white transition-colors hover:bg-primary/90"
                       href="<?= htmlspecialchars($item["url"]) ?>"
                       aria-current="<?= $is_active ? "page" : "false" ?>">
                        <?= htmlspecialchars($item["label"]) ?>
                    </a>
                <?php else: ?>
                    <a class="py-2 text-base font-medium transition-colors hover:text-primary <?= $is_active
                        ? "text-primary"
                        : "text-gray-700 dark:text-gray-200" ?>"
                       href="<?= htmlspecialchars($item["url"]) ?>"
                       aria-current="<?= $is_active ? "page" : "false" ?>">
                        <?= htmlspecialchars($item["label"]) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
    </div>
</header>

<!-- Mobile Menu JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            const isOpen = !mobileMenu.classList.contains('hidden');

            // Toggle menu visibility
            mobileMenu.classList.toggle('hidden', isOpen);

            // Update ARIA attribute for accessibility
            this.setAttribute('aria-expanded', String(!isOpen));

            // Toggle icon between hamburger and close
            if (menuIcon) {
                menuIcon.textContent = isOpen ? 'menu' : 'close';
            }
        });
    }
});
</script>
