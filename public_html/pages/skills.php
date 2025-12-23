<?php
/**
 * Skills & Certifications Page
 */
?>
<main class="flex flex-1 items-center justify-center py-8 md:py-12">
    <div class="w-full max-w-6xl px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <header class="w-full mb-8 md:mb-12">
            <h1 class="text-4xl font-black tracking-tight text-gray-900 dark:text-white sm:text-5xl">
                Skills & Certifications
            </h1>
            <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">
                Technical expertise validated through industry-recognized certifications and hands-on experience.
            </p>
        </header>

        <!-- Technical Skills -->
        <section id="technical-skills" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Technical Skills</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($config["skills"] as $category => $skills): ?>
                    <div class="flex flex-col gap-4 rounded-xl bg-white dark:bg-surface
                                border border-gray-200 dark:border-border p-6
                                hover:border-primary/30 dark:hover:border-primary/30 transition-all">
                        <h3 class="text-lg font-bold text-primary"><?= htmlspecialchars(
                            $category,
                        ) ?></h3>
                        <div class="flex flex-col gap-3">
                            <?php foreach ($skills as $skill): ?>
                                <div class="flex items-center justify-between gap-3 group">
                                    <div class="flex items-center gap-3">
                                        <!-- Skill Icon/Logo -->
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10">
                                            <?php if (
                                                !empty($skill["logo"])
                                            ): ?>
                                                <img src="https://skillicons.dev/icons?i=<?= $skill[
                                                    "logo"
                                                ] ?>"
                                                     alt="<?= htmlspecialchars(
                                                         $skill["name"],
                                                     ) ?>"
                                                     class="h-5 w-5">
                                            <?php else: ?>
                                                <span class="material-symbols-outlined text-primary text-sm">
                                                    code
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            <?= htmlspecialchars(
                                                $skill["name"],
                                            ) ?>
                                        </span>
                                    </div>
                                    <?php if ($skill["endorsements"] > 0): ?>
                                        <span class="text-xs text-gray-500 dark:text-muted">
                                            <?= $skill[
                                                "endorsements"
                                            ] ?> endorsements
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Certifications -->
        <section id="certifications">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Licenses & Certifications</h2>

                <!-- Search & Filter -->
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-sm">search</span>
                        </div>
                        <input id="cert-search"
                               class="block w-full sm:w-64 rounded-lg border border-gray-300 dark:border-border
                                      bg-white dark:bg-surface py-2 pl-10 pr-3 text-sm
                                      text-gray-900 dark:text-gray-200 placeholder-gray-500
                                      focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="Search certifications..."
                               type="text">
                    </div>

                    <div class="flex gap-2 flex-wrap" id="cert-filters">
                        <button class="filter-btn active" data-filter="all">All</button>
                        <button class="filter-btn" data-filter="Web Dev">Web Dev</button>
                        <button class="filter-btn" data-filter="AI/ML">AI/ML</button>
                        <button class="filter-btn" data-filter="Cloud">Cloud</button>
                        <button class="filter-btn" data-filter="Mobile">Mobile</button>
                    </div>
                </div>
            </div>

            <!-- Certification Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="cert-grid">
                <?php foreach ($config["certifications"] as $index => $cert): ?>
                    <article class="cert-card flex flex-col rounded-xl bg-white dark:bg-surface
                                    border border-gray-200 dark:border-border p-6
                                    hover:border-primary/30 dark:hover:border-primary/30
                                    hover:shadow-lg transition-all opacity-0 translate-y-4 animate-fade-in-up"
                             data-category="<?= htmlspecialchars(
                                 $cert["category"],
                             ) ?>"
                             data-title="<?= htmlspecialchars(
                                 strtolower($cert["title"]),
                             ) ?>"
                             style="animation-delay: <?= $index * 100 ?>ms">

                        <!-- Header -->
                        <div class="flex items-start justify-between gap-4">
                            <img src="<?= htmlspecialchars(
                                $cert["issuer_logo"],
                            ) ?>"
                                 alt="<?= htmlspecialchars(
                                     $cert["issuer"],
                                 ) ?> logo"
                                 class="h-10 w-auto">
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                                <?= htmlspecialchars($cert["category"]) ?>
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="mt-4 text-lg font-bold text-gray-900 dark:text-white leading-tight">
                            <?= htmlspecialchars($cert["title"]) ?>
                        </h3>

                        <!-- Date -->
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <?= htmlspecialchars($cert["date"]) ?>
                        </p>

                        <!-- Credential ID -->
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 font-mono">
                            ID: <?= htmlspecialchars($cert["credential_id"]) ?>
                        </p>

                        <!-- Skills -->
                        <?php if (!empty($cert["skills"])): ?>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <?php foreach ($cert["skills"] as $skill): ?>
                                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded">
                                        <?= htmlspecialchars($skill) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- View Credential Link -->
                        <a href="<?= htmlspecialchars($cert["url"]) ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline group">
                            View Credential
                            <span class="material-symbols-outlined text-base transition-transform group-hover:translate-x-1">
                                arrow_forward
                            </span>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <p class="mt-8 text-sm text-gray-500 dark:text-gray-400 text-center">
                Showing <span id="cert-count"><?= count(
                    $config["certifications"],
                ) ?></span> certifications
            </p>
        </section>

    </div>
</main>

<!-- Styles & Scripts -->
<style>
    /* Filter Buttons */
    .filter-btn {
        @apply flex h-8 items-center justify-center rounded-lg px-3 text-sm font-medium transition-colors;
        @apply bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300;
        @apply hover:bg-primary/20 hover:text-primary;
    }
    .filter-btn.active {
        @apply bg-primary/10 text-primary;
    }

    /* Animation */
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
// Certification search & filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('cert-search');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const certCards = document.querySelectorAll('.cert-card');
    const certCount = document.getElementById('cert-count');

    let currentFilter = 'all';

    function filterCerts() {
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;

        certCards.forEach(card => {
            const title = card.dataset.title;
            const category = card.dataset.category;

            const matchesSearch = title.includes(searchTerm);
            const matchesFilter = currentFilter === 'all' || category === currentFilter;

            if (matchesSearch && matchesFilter) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        certCount.textContent = visibleCount;
    }

    // Search input
    searchInput.addEventListener('input', filterCerts);

    // Filter buttons
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Update filter
            currentFilter = this.dataset.filter;
            filterCerts();
        });
    });
});
</script>
