<?php
/**
 * Experience Page - Professional Timeline
 */
?>
<main class="flex flex-1 justify-center pt-24 md:pt-36 py-8 md:py-12">
    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <header class="w-full mb-8 md:mb-12">
            <h1 class="text-4xl font-black tracking-tight text-gray-900 dark:text-white sm:text-5xl">
                Work Experience
            </h1>
            <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">
                My professional journey in software development and education
            </p>
        </header>

        <!-- Timeline Section -->
        <section class="relative" aria-label="Work experience timeline">
            <div class="pl-6 sm:pl-8 md:pl-12">

                <!-- Vertical Line -->
                <div class="absolute left-0 top-0 h-full w-0.5 bg-gray-300 dark:bg-gray-700 -translate-x-1/2 ml-4 md:ml-6"></div>

                <!-- Timeline Items -->
                <?php foreach ($config["experience"] as $index => $exp): ?>
                    <article class="relative mb-12 last:mb-0 group"
                             style="animation-delay: <?= $index * 100 ?>ms">

                        <!-- Timeline Dot -->
                        <div class="absolute left-0 top-6 h-5 w-5 rounded-full bg-primary -translate-x-1/2 ml-4 md:ml-6
                                    ring-4 ring-background-light dark:ring-background-dark
                                    transition-transform duration-300 group-hover:scale-110"></div>

                        <!-- Content Card -->
                        <div class="pl-12 md:pl-16">
                            <div class="opacity-0 translate-y-4 animate-fade-in-up">
                                <div class="bg-white dark:bg-surface rounded-xl border border-gray-200 dark:border-border
                                            shadow-sm hover:shadow-xl transition-all duration-300
                                            hover:border-primary/30 dark:hover:border-primary/30 p-6 md:p-8">

                                    <!-- Header -->
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                        <div>
                                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">
                                                <?= htmlspecialchars(
                                                    $exp["title"],
                                                ) ?>
                                            </h2>
                                            <p class="text-lg font-medium text-primary mt-1">
                                                <?= htmlspecialchars(
                                                    $exp["company"],
                                                ) ?>
                                            </p>
                                        </div>
                                        <div class="text-right md:text-right">
                                            <p class="text-sm font-medium text-gray-500 dark:text-muted">
                                                <?= htmlspecialchars(
                                                    $exp["date"],
                                                ) ?>
                                            </p>
                                            <?php if (
                                                isset($exp["location"])
                                            ): ?>
                                                <p class="text-xs text-gray-500 dark:text-muted mt-1">
                                                    <?= htmlspecialchars(
                                                        $exp["location"],
                                                    ) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <?php if (!empty($exp["description"])): ?>
                                        <ul class="mt-5 space-y-3" role="list">
                                            <?php foreach (
                                                $exp["description"]
                                                as $desc
                                            ): ?>
                                                <li class="flex items-start gap-3 text-gray-600 dark:text-muted">
                                                    <span class="material-symbols-outlined text-primary mt-0.5" style="font-size: 20px;">check_circle</span>
                                                    <span><?= htmlspecialchars(
                                                        $desc,
                                                    ) ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <!-- Skills -->
                                    <?php if (!empty($exp["skills"])): ?>
                                        <div class="mt-6 flex flex-wrap gap-2">
                                            <?php foreach (
                                                $exp["skills"]
                                                as $skill
                                            ): ?>
                                                <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1.5 text-sm font-medium text-primary
                                                            dark:bg-primary/20 dark:text-primary/90 transition-colors hover:bg-primary/20">
                                                    <?= htmlspecialchars(
                                                        $skill,
                                                    ) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>

            </div>
        </section>

    </div>
</main>

<!-- Animation Styles -->
<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
