<?php
/**
 * Games Page - Collection of Browser Games
 */
?>
<main class="flex flex-1 justify-center pt-24 md:pt-36 py-8 md:py-12">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <header class="w-full mb-8 md:mb-12 text-center">
            <h1 class="text-4xl font-black tracking-tight text-gray-900 dark:text-white sm:text-5xl">
                Games
            </h1>
            <p class="mt-3 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                A collection of fun browser-based games to take a break.
            </p>
        </header>

        <!-- Games Grid -->
        <section id="games-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" aria-label="Games showcase">
            <?php 
            $games = $config["games"] ?? [];
            if (empty($games)): 
            ?>
                <div class="col-span-full text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-slate-700 mb-4">sports_esports</span>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">Coming Soon</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Games are being developed. Check back later!</p>
                </div>
            <?php else: ?>
                <?php foreach ($games as $key => $game): ?>
                    <a href="/<?= htmlspecialchars($game["route"]) ?>" 
                       class="game-card group flex flex-col rounded-xl bg-white dark:bg-slate-800/50
                              border border-gray-200 dark:border-slate-800
                              shadow-sm hover:shadow-xl hover:border-primary/30
                              dark:hover:border-primary/30 transition-all duration-300
                              overflow-hidden p-6">
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary/10 dark:bg-primary/20 text-primary">
                                <span class="material-symbols-outlined text-2xl"><?= htmlspecialchars($game["icon"]) ?></span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                                    <?= htmlspecialchars($game["title"]) ?>
                                </h2>
                                <?php if (isset($game["status"])): ?>
                                    <?php
                                    $statusColors = [
                                        "new" => "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
                                        "popular" => "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400",
                                        "classic" => "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400",
                                    ];
                                    $statusClass = $statusColors[$game["status"]] ?? $statusColors["new"];
                                    ?>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?= $statusClass ?> mt-1">
                                        <?= ucfirst(htmlspecialchars($game["status"])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            <?= htmlspecialchars($game["description"]) ?>
                        </p>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

    </div>
</main>
