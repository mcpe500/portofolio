<?php
/**
 * Education Page - Academic Journey
 */
?>
<main class="flex flex-1 items-center justify-center py-8 md:py-12">
    <div class="w-full max-w-6xl px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <header class="w-full mb-8 md:mb-12 text-center">
            <h1 class="text-4xl font-black tracking-tight text-gray-900 dark:text-white sm:text-5xl">
                Education
            </h1>
            <p class="mt-3 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                My academic journey in computer science with a focus on practical, industry-relevant skills.
            </p>
        </header>

        <!-- Main Education Card -->
        <section class="mb-12">
            <div class="relative rounded-xl bg-white dark:bg-slate-800/50
                         border border-gray-200 dark:border-slate-800
                         shadow-sm hover:shadow-lg transition-all p-8">

                <!-- Timeline dot -->
                <div class="absolute -left-4 top-8 hidden lg:block">
                    <div class="h-6 w-6 rounded-full bg-primary ring-4 ring-background-light dark:ring-background-dark"></div>
                </div>

                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <?= htmlspecialchars($config['education']['degree']) ?>
                        </h2>
                        <p class="text-lg text-primary font-medium">
                            <?= htmlspecialchars($config['education']['institution']) ?>
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <?= htmlspecialchars($config['education']['period']) ?>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">trophy</span>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">
                            GPA: <?= htmlspecialchars($config['education']['gpa']) ?>
                        </span>
                    </div>
                </div>

                <!-- Achievements -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">stars</span>
                        Academic Achievements
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($config['education']['achievements'] as $achievement): ?>
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary mt-0.5">check_circle</span>
                                <p class="text-gray-600 dark:text-gray-400">
                                    <?= htmlspecialchars($achievement) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Semester Details -->
        <section id="semesters">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Semester Coursework</h2>
                <button id="expand-all" class="text-sm font-medium text-primary hover:text-primary/80 transition-colors">
                    Expand All
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($config['education']['semesters'] as $index => $semester): ?>
                    <div class="semester-card opacity-0 translate-y-4 animate-fade-in-up"
                         style="animation-delay: <?= $index * 100 ?>ms">
                        <div class="group flex flex-col h-full rounded-xl bg-white dark:bg-slate-800/50
                                    border border-gray-200 dark:border-slate-800
                                    hover:border-primary/30 dark:hover:border-primary/30
                                    transition-all overflow-hidden">

                            <!-- Header -->
                            <div class="flex items-center justify-between p-5 cursor-pointer"
                                 onclick="toggleSemester(<?= $semester['number'] ?>)">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10">
                                        <span class="text-sm font-bold text-primary"><?= $semester['number'] ?></span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Semester <?= $semester['number'] ?>
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?= htmlspecialchars($semester['name']) ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 transition-transform duration-300 transform semester-arrow-<?= $semester['number'] ?>">
                                    expand_more
                                </span>
                            </div>

                            <!-- Collapsible Content -->
                            <div id="semester-<?= $semester['number'] ?>"
                                 class="semester-content hidden px-5 pb-5 border-t border-gray-100 dark:border-slate-800">

                                <!-- Description -->
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <?= htmlspecialchars($semester['description']) ?>
                                </p>

                                <!-- Courses -->
                                <div class="space-y-2">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Key Courses:</h4>
                                    <?php foreach ($semester['courses'] as $course): ?>
                                        <?php
                                        // Check if course contains a link (like Digital Systems)
                                        if (preg_match('/\((https?:\/\/[^\)]+)\)/', $course, $matches)) {
                                            $course_name = trim(str_replace($matches[0], '', $course));
                                            $course_link = $matches[1];
                                            echo '<a href="' . htmlspecialchars($course_link) . '" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">';
                                            echo '<span class="material-symbols-outlined text-primary text-base">link</span>';
                                            echo '<span class="text-sm">' . htmlspecialchars($course_name) . '</span>';
                                            echo '</a>';
                                        } else {
                                            echo '<div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">';
                                            echo '<span class="material-symbols-outlined text-primary text-base">check_circle</span>';
                                            echo '<span class="text-sm">' . htmlspecialchars($course) . '</span>';
                                            echo '</div>';
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
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

<!-- JavaScript for Accordion -->
<script>
    function toggleSemester(semesterNumber) {
        const content = document.getElementById(`semester-${semesterNumber}`);
        const arrow = document.querySelector(`.semester-arrow-${semesterNumber}`);
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            content.classList.add('block');
            arrow.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            content.classList.remove('block');
            arrow.classList.remove('rotate-180');
        }
    }

    document.getElementById('expand-all')?.addEventListener('click', function() {
        const semesterCards = document.querySelectorAll('.semester-content');
        const arrows = document.querySelectorAll('[class*="semester-arrow-"]');
        const isExpanded = this.textContent === 'Collapse All';

        semesterCards.forEach(card => {
            if (isExpanded) {
                card.classList.add('hidden');
                card.classList.remove('block');
            } else {
                card.classList.remove('hidden');
                card.classList.add('block');
            }
        });

        arrows.forEach(arrow => {
            if (isExpanded) {
                arrow.classList.remove('rotate-180');
            } else {
                arrow.classList.add('rotate-180');
            }
        });

        this.textContent = isExpanded ? 'Expand All' : 'Collapse All';
    });
</script>