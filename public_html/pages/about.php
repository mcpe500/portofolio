<?php
/**
 * About Page - Bio and Skills
 */

// Consolidate all skills from experience and remove duplicates
$all_skills = [];
foreach ($config['experience'] as $exp) {
    if (!empty($exp['skills'])) {
        $all_skills = array_merge($all_skills, $exp['skills']);
    }
}
$unique_skills = array_unique($all_skills);
sort($unique_skills);

// Categorize skills for better presentation
$categorized_skills = [
    'Frontend' => ['React.js', 'Next.js', 'JavaScript', 'Tailwind CSS', 'Mantine', 'Responsive Design'],
    'Backend' => ['Node.js', 'PHP', 'Laravel', 'Python', 'Java', 'Spring Boot', 'tRPC'],
    'DevOps & Tools' => ['AWS', 'Linux', 'Docker', 'Agile', 'Git'],
    'Other' => ['Teaching', 'Mentorship', 'Curriculum Development', 'Algorithms', 'Unit Testing']
];

// Filter unique skills into categories
$skills_in_categories = [];
$uncategorized_skills = $unique_skills;

foreach ($categorized_skills as $category => $skills_in_category) {
    $found_skills = array_intersect($skills_in_category, $unique_skills);
    if (!empty($found_skills)) {
        $skills_in_categories[$category] = $found_skills;
        $uncategorized_skills = array_diff($uncategorized_skills, $found_skills);
    }
}

// Add remaining skills to 'Other'
if (!empty($uncategorized_skills)) {
    if (!isset($skills_in_categories['Other'])) {
        $skills_in_categories['Other'] = [];
    }
    $skills_in_categories['Other'] = array_unique(array_merge($skills_in_categories['Other'], $uncategorized_skills));
    sort($skills_in_categories['Other']);
}

?>
<main class="flex flex-1 items-center justify-center py-8 md:py-12">
    <div class="w-full max-w-6xl px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <header class="w-full mb-8 md:mb-12">
            <h1 class="text-4xl font-black tracking-tight text-gray-900 dark:text-white sm:text-5xl">
                About Me
            </h1>
            <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">
                A little more about my journey and capabilities.
            </p>
        </header>

        <!-- Bio Section -->
        <section class="mb-12">
            <div class="bg-white dark:bg-surface rounded-xl border border-gray-200 dark:border-border p-6 md:p-8">
                <p class="text-lg text-gray-700 dark:text-muted leading-relaxed">
                    <?= htmlspecialchars($config['site']['bio']) ?>
                </p>
            </div>
        </section>



    </div>
</main>
