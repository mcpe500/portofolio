<?php
/**
 * Homepage Template
 * @param array $config Site configuration
 */
?>
<main class="flex flex-1 items-center justify-center">
    <div class="flex w-full max-w-5xl flex-col items-center px-4 py-16 text-center sm:px-6 lg:px-8">

        <!-- HeroSection -->
        <section class="flex max-w-3xl flex-col items-center gap-6" aria-labelledby="hero-title">
            <h1 id="hero-title" class="text-4xl font-black tracking-tighter text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                <?= strtoupper(htmlspecialchars($config["site"]["name"])) ?>
            </h1>
            <p class="text-lg font-medium text-primary sm:text-xl"><?= htmlspecialchars(
                $config["site"]["description"],
            ) ?></p>
            <p class="text-base text-gray-600 dark:text-gray-400 sm:text-lg">
                <?= htmlspecialchars($config["site"]["bio"]) ?>
            </p>
            <button class="flex h-12 min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-primary px-6 text-base font-bold text-white transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark">
                <span class="truncate">View My Work</span>
            </button>
        </section>

        <!-- ActionsBar -->
        <section class="mt-12 flex flex-wrap justify-center gap-4 sm:gap-6" aria-label="Social links">
            <?php foreach ($config["social_links"] as $link): ?>
                <a class="group flex flex-col items-center gap-2 text-center focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded-lg p-1 dark:focus:ring-offset-background-dark"
                   href="<?= htmlspecialchars($link["url"]) ?>"
                   title="<?= htmlspecialchars($link["title"]) ?>"
                   rel="noopener noreferrer">
                    <div class="flex size-12 items-center justify-center rounded-full bg-gray-200/50 transition-colors group-hover:bg-primary/20 dark:bg-gray-800/50 dark:group-hover:bg-primary/20">
                        <span class="material-symbols-outlined text-gray-600 transition-colors group-hover:text-primary dark:text-gray-300 dark:group-hover:text-primary" style="font-size: 24px;">
                            <?= htmlspecialchars($link["icon"]) ?>
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400"><?= htmlspecialchars(
                        $link["label"],
                    ) ?></p>
                </a>
            <?php endforeach; ?>
        </section>

    </div>
</main>
