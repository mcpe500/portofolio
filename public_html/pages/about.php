<?php
/**
 * About Page - Personal Story & AI Focus
 */
?>
<main class="flex-grow pt-16">
    <!-- Hero Section -->
    <section class="py-20 px-6 bg-white dark:bg-gray-950">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Image -->
                <div class="order-2 lg:order-1">
                    <img src="https://picsum.photos/seed/coding-setup/600/700" alt="Coding Setup" class="w-full rounded-3xl shadow-2xl object-cover">
                </div>
                
                <!-- Content -->
                <div class="order-1 lg:order-2">
                    <span class="text-blue-600 font-bold text-sm uppercase tracking-wider">About Me</span>
                    <h1 class="text-4xl md:text-5xl font-bold mt-3 mb-6 text-gray-900 dark:text-white">
                        Passionate Developer, <br><span class="text-gradient">AI Enthusiast.</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                        I don't just write code; I build solutions. In an era where technology evolves rapidly, I position myself at the intersection of solid backend engineering and the cutting edge of Artificial Intelligence.
                    </p>
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        My journey involves building complex full-stack applications, managing complex PostgreSQL databases, and utilizing modern frameworks. However, what sets me apart is my ability to harness the power of <strong class="text-gray-900 dark:text-white">Large Language Models (LLMs)</strong> to accelerate development, generate boilerplate code, and debug complex systems efficiently.
                    </p>
                    
                    <!-- Highlight Cards -->
                    <div class="grid sm:grid-cols-2 gap-6 mb-8">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-code text-2xl text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">Clean Code</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Maintainable & Scalable</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-rocket text-2xl text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">Fast Delivery</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">AI-Assisted Workflow</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="https://www.linkedin.com/in/ivan-santoso-53bb27223/" target="_blank" class="inline-flex items-center gap-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-6 py-3 rounded-full font-semibold hover:opacity-90 transition-opacity">
                        <i class="fab fa-linkedin"></i> Connect on LinkedIn
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- What I Do Section -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-blue-600 font-bold text-sm uppercase tracking-wider">What I Do</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900 dark:text-white">My Core Competencies</h2>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Backend Development -->
                <div class="glass-card p-8 dark:bg-gray-900/50">
                    <div class="text-4xl text-blue-500 mb-4">
                        <i class="fas fa-server"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Backend Development</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Building secure, high-performance server-side applications using PHP, Node.js, and modern frameworks.</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="skill-badge">PHP</span>
                        <span class="skill-badge">PostgreSQL</span>
                        <span class="skill-badge">Node.js</span>
                    </div>
                </div>
                
                <!-- Frontend Development -->
                <div class="glass-card p-8 dark:bg-gray-900/50">
                    <div class="text-4xl text-blue-500 mb-4">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Frontend & UI</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Creating responsive, modern user interfaces with attention to UX and accessibility.</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="skill-badge">React.js</span>
                        <span class="skill-badge">Tailwind</span>
                        <span class="skill-badge">JavaScript</span>
                    </div>
                </div>
                
                <!-- AI & LLM -->
                <div class="glass-card p-8 dark:bg-gray-900/50 md:col-span-2 lg:col-span-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/20 to-blue-500/20 rounded-full blur-2xl"></div>
                    <div class="relative">
                        <div class="text-4xl text-purple-500 mb-4">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">AI & LLM Integration</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Leveraging AI to accelerate development, automate workflows, and build intelligent features.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="skill-badge">Prompt Engineering</span>
                            <span class="skill-badge">AI Coding</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Personal Section -->
    <section class="py-20 px-6 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-4xl mx-auto text-center">
            <span class="text-blue-600 font-bold text-sm uppercase tracking-wider">Beyond Code</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-8 text-gray-900 dark:text-white">A Few Things I Enjoy</h2>
            
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="glass-card p-6 dark:bg-gray-900/50">
                    <div class="text-3xl mb-3">🎮</div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Gaming</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Strategy & RPGs</p>
                </div>
                <div class="glass-card p-6 dark:bg-gray-900/50">
                    <div class="text-3xl mb-3">📚</div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Learning</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Always exploring new tech</p>
                </div>
                <div class="glass-card p-6 dark:bg-gray-900/50">
                    <div class="text-3xl mb-3">☕</div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Coffee</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fuel for coding sessions</p>
                </div>
            </div>
        </div>
    </section>
</main>
