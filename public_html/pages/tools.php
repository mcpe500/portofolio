<?php
/**
 * Tools Page - Developer Utilities
 */
?>
<main class="flex flex-1 justify-center pt-24 md:pt-36 py-8 md:py-12">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <header class="w-full mb-8 md:mb-12 text-center">
            <h1 class="text-4xl font-black tracking-tight text-gray-900 dark:text-white sm:text-5xl">
                Developer Tools
            </h1>
            <p class="mt-3 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                A collection of browser-based utilities for developers.
            </p>
        <!-- Search Bar -->
        <div class="relative max-w-xl mx-auto mb-12">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </div>
            <input type="text" 
                   id="tool-search"
                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-700 rounded-xl leading-5 bg-white dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm shadow-sm transition-all"
                   placeholder="Search tools (e.g., 'calculator', 'react', 'converter')...">
        </div>

        <!-- Tools Grid -->
        <section id="tools-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" aria-label="Tools showcase">
            <?php foreach ($config["tools"] as $key => $tool): ?>
                <a href="/<?= htmlspecialchars($tool["route"]) ?>" 
                   data-title="<?= htmlspecialchars(strtolower($tool["title"])) ?>"
                   data-desc="<?= htmlspecialchars(strtolower($tool["description"])) ?>"
                   data-status="<?= htmlspecialchars($tool["status"]) ?>"
                   class="tool-card group flex flex-col rounded-xl bg-white dark:bg-slate-800/50
                          border border-gray-200 dark:border-slate-800
                          shadow-sm hover:shadow-xl hover:border-primary/30
                          dark:hover:border-primary/30 transition-all duration-300
                          overflow-hidden p-6">
                    
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary/10 dark:bg-primary/20 text-primary">
                            <span class="material-symbols-outlined text-2xl"><?= htmlspecialchars($tool["icon"]) ?></span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                                <?= htmlspecialchars($tool["title"]) ?>
                            </h2>
                            <?php if (isset($tool["status"])): ?>
                                <?php
                                $statusColors = [
                                    "stable" => "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
                                    "experimental" => "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400",
                                    "legacy" => "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
                                ];
                                $statusClass = $statusColors[$tool["status"]] ?? $statusColors["stable"];
                                ?>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?= $statusClass ?> mt-1">
                                    <?= ucfirst(htmlspecialchars($tool["status"])) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        <?= htmlspecialchars($tool["description"]) ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </section>
        
        <!-- No Results Message -->
        <div id="no-results" class="hidden text-center py-12">
            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-slate-700 mb-4">search_off</span>
            <h3 class="text-xl font-medium text-gray-900 dark:text-white">No tools found</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Try adjusting your search terms.</p>
        </div>

    </div>
</main>

<script>
    const searchInput = document.getElementById('tool-search');
    const toolCards = document.querySelectorAll('.tool-card');
    const noResults = document.getElementById('no-results');

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();
        let visibleCount = 0;

        toolCards.forEach(card => {
            const title = card.dataset.title;
            const desc = card.dataset.desc;
            const status = card.dataset.status;
            
            if (title.includes(query) || desc.includes(query) || status.includes(query)) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    });
</script>
