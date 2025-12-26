<?php
/**
 * Homepage Template - AI-Focused Portfolio
 * @param array $config Site configuration
 */
?>
<main class="flex-grow pt-16">
    <!-- Hero Section -->
    <section class="relative min-h-[80vh] flex items-center" style="background: radial-gradient(circle at top right, rgba(76, 201, 240, 0.08), transparent 40%), radial-gradient(circle at bottom left, rgba(67, 97, 238, 0.08), transparent 40%);">
        <div class="max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1">
                <!-- AI Tag -->
                <div class="ai-tag mb-6">
                    <i class="fas fa-robot"></i>
                    AI & LLM Proficient
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight leading-tight mb-6 text-gray-900 dark:text-white">
                    Building the Future with <span class="text-gradient">Code & Intelligence</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-xl">
                    Hi, I'm <strong class="text-gray-900 dark:text-white">Ivan Santoso</strong>. A Full Stack Developer who bridges the gap between traditional web architecture and modern AI capabilities.
                </p>
                
                <div class="flex flex-wrap gap-4 mb-10">
                    <a href="/projects" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-full font-semibold transition-all flex items-center gap-2 shadow-lg shadow-blue-500/25">
                        View My Work <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="/contact" class="border border-gray-300 dark:border-gray-700 px-8 py-4 rounded-full font-medium hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-900 dark:text-white">
                        Contact Me
                    </a>
                </div>
                
                <!-- Social Proof -->
                <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                    <a href="https://www.linkedin.com/in/ivan-santoso-53bb27223/" target="_blank" class="flex items-center gap-2 hover:text-blue-600 transition-colors">
                        <i class="fab fa-linkedin text-lg"></i> Connect on LinkedIn
                    </a>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-database"></i> PostgreSQL Expert
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fab fa-php text-lg"></i> Backend Architect
                    </span>
                </div>
            </div>
            
            <div class="order-1 lg:order-2 flex justify-center">
                <!-- Hero Image with Morph Animation -->
                <div class="relative">
                    <img src="https://picsum.photos/seed/ivan-dev/500/500" alt="Ivan Santoso" class="hero-img w-72 h-72 md:w-96 md:h-96 object-cover shadow-2xl">
                    <!-- Decorative Elements -->
                    <div class="absolute -z-10 inset-0 bg-gradient-to-br from-blue-500/30 to-purple-500/30 blur-3xl animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Props Section -->
    <section class="py-20 px-6 bg-white dark:bg-gray-950">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- AI Integration -->
                <div class="glass-card p-8 text-center dark:bg-gray-900/50">
                    <div class="text-5xl text-blue-500 mb-4">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">AI Integration</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Leveraging Large Language Models (LLMs) to automate tasks, generate code, and build intelligent web features.
                    </p>
                </div>
                
                <!-- Robust Backend -->
                <div class="glass-card p-8 text-center dark:bg-gray-900/50">
                    <div class="text-5xl text-blue-500 mb-4">
                        <i class="fas fa-server"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Robust Backend</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Architecting secure, scalable PHP applications powered by PostgreSQL and MySQL database systems.
                    </p>
                </div>
                
                <!-- Dynamic Tooling -->
                <div class="glass-card p-8 text-center dark:bg-gray-900/50">
                    <div class="text-5xl text-blue-500 mb-4">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Dynamic Tooling</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Creating practical web-based tools—from calculators to data converters—that solve real engineering problems.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Projects Section -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-blue-600 font-bold text-sm uppercase tracking-wider">Portfolio</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900 dark:text-white">Featured Tools & Applications</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-3">A glimpse into my development capabilities.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Tool 1: Calculator -->
                <div class="glass-card p-6 dark:bg-gray-900/50">
                    <span class="inline-block px-3 py-1 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 text-xs font-semibold rounded-full mb-4">Calculator</span>
                    <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-white">Scientific & Graphing Calc</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Advanced mathematical logic handling complex operations directly in the browser.</p>
                    <a href="/tools/calc/scientific" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">Try Tool →</a>
                </div>
                
                <!-- Tool 2: Data Converter -->
                <div class="glass-card p-6 dark:bg-gray-900/50">
                    <span class="inline-block px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold rounded-full mb-4">Data</span>
                    <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-white">JSON & XML Converter</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Efficient data parsing and conversion tools for developer workflows.</p>
                    <a href="/tools/data-converter" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">Try Tool →</a>
                </div>
                
                <!-- Tool 3: Hash & Crypto -->
                <div class="glass-card p-6 dark:bg-gray-900/50">
                    <span class="inline-block px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-full mb-4">Security</span>
                    <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-white">Hash & Crypto</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Secure hashing tools demonstrating understanding of cybersecurity principles.</p>
                    <a href="/tools/hash-crypto" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">Try Tool →</a>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="/tools" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-full font-semibold transition-all shadow-lg shadow-blue-500/25">
                    View All 40+ Tools
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Project Card -->
    <section class="py-20 px-6 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Featured Project</h2>
                <a href="/projects" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">View all →</a>
            </div>
            
            <?php if (!empty($config["projects"][0])): 
                $project = $config["projects"][0];
            ?>
            <a href="<?= htmlspecialchars($project["links"][0]["url"] ?? "#") ?>" class="group block glass-card overflow-hidden grid md:grid-cols-2 dark:bg-gray-900/50">
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <div class="flex gap-2 mb-4 flex-wrap">
                        <?php foreach (array_slice($project["skills"], 0, 3) as $skill): ?>
                            <span class="skill-badge"><?= htmlspecialchars($skill) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <h3 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white"><?= htmlspecialchars($project["title"]) ?></h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6"><?= htmlspecialchars($project["description"]) ?></p>
                    <span class="text-blue-600 font-semibold">View Case Study →</span>
                </div>
                <div class="bg-gray-200 dark:bg-gray-800 min-h-[300px] relative overflow-hidden">
                    <?php if (!empty($project["image"])): ?>
                        <img src="<?= htmlspecialchars($project["image"]) ?>" alt="<?= htmlspecialchars($project["alt"] ?? "") ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <?php else: ?>
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-6xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </section>
</main>
