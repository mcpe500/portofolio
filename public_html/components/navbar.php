<?php
/**
 * Navigation Bar Component
 * @param array $config Site configuration
 * @param string $current_page Current page identifier
 */
?>
<header class="fixed top-0 left-0 w-full z-50 backdrop-blur-md bg-bg/80 border-b border-border transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <!-- Logo & Name -->
        <a href="/" class="flex items-center gap-2 group">
            <div class="w-2 h-2 bg-white rounded-full group-hover:bg-primary transition-colors"></div>
            <span class="text-sm font-bold tracking-tight text-white group-hover:opacity-80 transition-opacity">
                <?= htmlspecialchars($config["site"]["name"] ?? "Site Name") ?>
            </span>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-muted">
            <?php foreach ($config["navigation"] ?? [] as $item): ?>
                <?php
                $path = parse_url($item["url"] ?? "#", PHP_URL_PATH);
                $is_active = trim($path, "/") === ($current_page ?? "");
                $type = $item["type"] ?? "link";
                ?>

                <?php if ($type === "button"): ?>
                    <a href="<?= htmlspecialchars($item["url"]) ?>"
                       class="text-white hover:text-primary transition-colors <?= $is_active ? "text-white" : "" ?>">
                        <?= htmlspecialchars($item["label"]) ?>
                    </a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($item["url"]) ?>"
                       class="hover:text-white transition-colors <?= $is_active ? "text-white" : "" ?>">
                        <?= htmlspecialchars($item["label"]) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <!-- Theme Settings Button -->
            <button id="theme-btn" class="hover:text-white transition-colors" aria-label="Theme Settings">
                <i class="ph ph-gear text-lg"></i>
            </button>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center gap-4">
            <button id="theme-btn-mobile" class="text-muted hover:text-white transition-colors">
                <i class="ph ph-gear text-lg"></i>
            </button>
            <button id="mobile-menu-toggle" class="text-white p-2">
                <i class="ph ph-list text-xl" id="menu-icon"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden border-b border-border bg-bg p-4 absolute w-full">
        <div class="flex flex-col gap-4 text-sm font-medium text-muted">
            <?php foreach ($config["navigation"] ?? [] as $item): ?>
                <a href="<?= htmlspecialchars($item["url"]) ?>" 
                   class="text-left hover:text-white transition-colors">
                    <?= htmlspecialchars($item["label"]) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</header>

<!-- Theme Settings Modal -->
<div id="theme-modal" class="fixed inset-0 z-[60] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="theme-backdrop"></div>
    
    <!-- Modal Content -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-surface border border-border rounded-xl p-6 shadow-2xl transform transition-all scale-95 opacity-0" id="theme-content">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-white">Appearance</h3>
            <button id="close-theme" class="text-muted hover:text-white">
                <i class="ph ph-x text-lg"></i>
            </button>
        </div>

        <!-- Mode Selection -->
        <div class="mb-6">
            <label class="block text-xs font-bold text-muted uppercase tracking-wider mb-3">Theme</label>
            <div class="grid grid-cols-3 gap-2 bg-bg p-1 rounded-lg border border-border">
                <button class="theme-mode-btn flex items-center justify-center gap-2 py-2 rounded-md text-sm font-medium text-muted hover:text-white transition-colors" data-mode="light">
                    <i class="ph ph-sun"></i> Light
                </button>
                <button class="theme-mode-btn flex items-center justify-center gap-2 py-2 rounded-md text-sm font-medium text-muted hover:text-white transition-colors" data-mode="dark">
                    <i class="ph ph-moon"></i> Dark
                </button>
                <button class="theme-mode-btn flex items-center justify-center gap-2 py-2 rounded-md text-sm font-medium text-muted hover:text-white transition-colors" data-mode="system">
                    <i class="ph ph-desktop"></i> Auto
                </button>
            </div>
        </div>

        <!-- Color Selection -->
        <div>
            <label class="block text-xs font-bold text-muted uppercase tracking-wider mb-3">Accent Color</label>
            <div class="grid grid-cols-4 gap-3">
                <button class="color-btn w-full aspect-square rounded-full border-2 border-transparent hover:scale-110 transition-transform" style="background-color: #2563eb;" data-color="#2563eb" title="Blue"></button>
                <button class="color-btn w-full aspect-square rounded-full border-2 border-transparent hover:scale-110 transition-transform" style="background-color: #16a34a;" data-color="#16a34a" title="Green"></button>
                <button class="color-btn w-full aspect-square rounded-full border-2 border-transparent hover:scale-110 transition-transform" style="background-color: #9333ea;" data-color="#9333ea" title="Purple"></button>
                <button class="color-btn w-full aspect-square rounded-full border-2 border-transparent hover:scale-110 transition-transform" style="background-color: #ea580c;" data-color="#ea580c" title="Orange"></button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Logic
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            const isOpen = !mobileMenu.classList.contains('hidden');
            if (menuIcon) {
                menuIcon.classList.toggle('ph-list', !isOpen);
                menuIcon.classList.toggle('ph-x', isOpen);
            }
        });
    }

    // Theme Modal Logic
    const themeBtns = [document.getElementById('theme-btn'), document.getElementById('theme-btn-mobile')];
    const themeModal = document.getElementById('theme-modal');
    const themeContent = document.getElementById('theme-content');
    const closeTheme = document.getElementById('close-theme');
    const backdrop = document.getElementById('theme-backdrop');

    function openModal() {
        themeModal.classList.remove('hidden');
        // Small delay for animation
        setTimeout(() => {
            themeContent.classList.remove('scale-95', 'opacity-0');
            themeContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        updateActiveStates();
    }

    function closeModal() {
        themeContent.classList.remove('scale-100', 'opacity-100');
        themeContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            themeModal.classList.add('hidden');
        }, 200);
    }

    themeBtns.forEach(btn => btn?.addEventListener('click', openModal));
    closeTheme?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);

    // Theme Logic
    const modeBtns = document.querySelectorAll('.theme-mode-btn');
    const colorBtns = document.querySelectorAll('.color-btn');

    function updateActiveStates() {
        const config = window.themeConfig;
        
        // Mode Buttons
        modeBtns.forEach(btn => {
            if (btn.dataset.mode === config.mode) {
                btn.classList.add('bg-surface', 'text-white', 'shadow-sm');
                btn.classList.remove('text-muted');
            } else {
                btn.classList.remove('bg-surface', 'text-white', 'shadow-sm');
                btn.classList.add('text-muted');
            }
        });

        // Color Buttons
        colorBtns.forEach(btn => {
            if (btn.dataset.color === config.primary) {
                btn.classList.add('border-white');
                btn.classList.remove('border-transparent');
            } else {
                btn.classList.remove('border-white');
                btn.classList.add('border-transparent');
            }
        });
    }

    function saveConfig(newConfig) {
        window.themeConfig = { ...window.themeConfig, ...newConfig };
        localStorage.setItem('theme_config', JSON.stringify(window.themeConfig));
        
        // Apply changes
        if (newConfig.mode) {
            if (newConfig.mode === 'dark' || (newConfig.mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        if (newConfig.primary) {
            location.reload(); 
        }
        
        updateActiveStates();
    }

    modeBtns.forEach(btn => {
        btn.addEventListener('click', () => saveConfig({ mode: btn.dataset.mode }));
    });

    colorBtns.forEach(btn => {
        btn.addEventListener('click', () => saveConfig({ primary: btn.dataset.color }));
    });
});
</script>
