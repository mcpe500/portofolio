<?php
/**
 * Homepage Template
 * @param array $config Site configuration
 */
?>
<main class="flex-grow pt-16">
    <!-- Hero Section -->
    <section id="home" class="page-section active animate-fade-in">
        <header class="relative pt-24 pb-20 md:pt-40 md:pb-32 px-6 border-b border-border">
            <div class="max-w-7xl mx-auto">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-border bg-surface/50 text-xs font-medium text-muted mb-8">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Open to Opportunities
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold tracking-tighter leading-[1.1] mb-8 text-gray-900 dark:text-white">
                    Building Scalable & Robust <span class="text-muted">Digital Solutions</span>.
                </h1>
                <p class="text-xl text-gray-600 dark:text-muted max-w-2xl leading-relaxed mb-10">
                    <?= htmlspecialchars($config["site"]["bio"]) ?>
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="/contact" class="bg-gray-900 dark:bg-white text-white dark:text-black px-8 py-4 rounded-full font-semibold hover:opacity-90 transition-opacity flex items-center gap-2">
                        Get in touch <i class="ph ph-arrow-right"></i>
                    </a>
                    <a href="/projects" class="border border-gray-200 dark:border-border px-8 py-4 rounded-full font-medium hover:bg-gray-100 dark:hover:bg-surface transition-colors text-gray-900 dark:text-white">
                        See my work
                    </a>
                </div>
            </div>
        </header>

        <div class="py-24 px-6 max-w-7xl mx-auto">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Featured Project</h2>
                <a href="/projects" class="text-sm text-muted hover:text-primary border-b border-transparent hover:border-primary pb-1 transition-all">View all</a>
            </div>
            
            <!-- Featured Card -->
            <?php if (!empty($config["projects"][0])): 
                $project = $config["projects"][0];
            ?>
            <a href="<?= htmlspecialchars($project["links"][0]["url"] ?? "#") ?>" class="group block border border-gray-200 dark:border-border bg-white dark:bg-surface/30 rounded-2xl overflow-hidden grid md:grid-cols-2 hover:border-primary/50 transition-colors">
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <div class="flex gap-2 mb-4 flex-wrap">
                        <?php foreach (array_slice($project["skills"], 0, 3) as $skill): ?>
                            <span class="text-xs font-mono border border-gray-200 dark:border-border px-2 py-1 rounded bg-gray-50 dark:bg-bg text-gray-600 dark:text-muted"><?= htmlspecialchars($skill) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <h3 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white"><?= htmlspecialchars($project["title"]) ?></h3>
                    <p class="text-gray-600 dark:text-muted mb-6"><?= htmlspecialchars($project["description"]) ?></p>
                    <span class="text-sm font-bold underline underline-offset-4 text-primary">View Case Study</span>
                </div>
                <div class="bg-gray-100 dark:bg-neutral-800 min-h-[300px] relative overflow-hidden">
                    <?php if (!empty($project["image"])): ?>
                        <img src="<?= htmlspecialchars($project["image"]) ?>" alt="<?= htmlspecialchars($project["alt"] ?? "") ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <?php else: ?>
                        <div class="absolute inset-0 flex items-center justify-center text-neutral-400 dark:text-neutral-600">
                            <i class="ph ph-image text-6xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </section>
</main>
