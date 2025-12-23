<?php
/**
 * Projects Page - Portfolio Showcase
 */
?>
<main class="flex flex-1 items-center justify-center py-8 md:py-12">
    <div class="w-full max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <header class="w-full mb-8 md:mb-12 text-center">
            <h1 class="text-4xl font-black tracking-tight text-gray-900 dark:text-white sm:text-5xl">
                My Projects
            </h1>
            <p class="mt-3 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                A selection of applications I've built, showcasing my journey in mobile, web, and graphics development.
            </p>
        </header>

        <!-- Projects Grid -->
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6 md:gap-8" aria-label="Project showcase">
            <?php foreach ($config["projects"] as $index => $project): ?>
                <article class="group flex flex-col rounded-xl bg-white dark:bg-surface border border-gray-200 dark:border-border
                                shadow-sm hover:shadow-2xl hover:border-primary/30
                                dark:hover:border-primary/30 transition-all duration-300
                                overflow-hidden opacity-0 translate-y-4 animate-fade-in-up"
                         style="animation-delay: <?= $index * 150 ?>ms">

                    <!-- Project Image -->
                    <?php if (!empty($project["image"])): ?>
                        <div class="overflow-hidden">
                            <img class="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-105"
                                 src="<?= htmlspecialchars(
                                     $project["image"],
                                 ) ?>"
                                 alt="<?= htmlspecialchars($project["alt"]) ?>"
                                 loading="lazy">
                        </div>
                    <?php endif; ?>

                    <!-- Project Content -->
                    <div class="flex flex-col p-6 gap-4 flex-grow">

                        <!-- Header -->
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                                <?= htmlspecialchars($project["title"]) ?>
                            </h2>
                            <p class="text-sm font-medium text-primary mt-1">
                                <?= htmlspecialchars($project["date"]) ?>
                            </p>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 dark:text-muted text-sm font-normal leading-relaxed flex-grow">
                            <?= htmlspecialchars($project["description"]) ?>
                        </p>

                        <!-- Skills -->
                        <?php if (!empty($project["skills"])): ?>
                            <div class="flex flex-wrap items-center gap-2">
                                <?php foreach ($project["skills"] as $skill): ?>
                                    <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary
                                                dark:bg-primary/20 dark:text-primary/90 transition-colors hover:bg-primary/20">
                                        <?= htmlspecialchars($skill) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Action Links -->
                        <?php if (!empty($project["links"])): ?>
                            <div class="flex items-center gap-4 pt-2">
                                <?php foreach ($project["links"] as $link): ?>
                                    <?php
                                    $is_primary = $link["type"] === "demo";
                                    $icon =
                                        $link["type"] === "demo"
                                            ? "open_in_new"
                                            : "code";
                                    $button_class = $is_primary
                                        ? "bg-primary text-white hover:bg-primary/90"
                                        : "bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600";
                                    ?>
                                    <a class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden
                                           rounded-lg h-10 px-4 text-sm font-medium leading-normal transition-colors
                                           <?= $button_class ?> focus:outline-none focus:ring-2 focus:ring-primary"
                                       href="<?= htmlspecialchars(
                                           $link["url"],
                                       ) ?>"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       title="<?= htmlspecialchars(
                                           $link["label"],
                                       ) ?>">
                                        <span class="material-symbols-outlined text-base"><?= $icon ?></span>
                                        <span class="truncate"><?= htmlspecialchars(
                                            $link["label"],
                                        ) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </article>
            <?php endforeach; ?>
        </section>

    </div>
</main>

<!-- Animation Styles -->
<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
