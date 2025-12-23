<?php
/**
 * Head Component - Metadata & Assets
 * @param array $config Site configuration
 * @param string $page_title Page-specific title
 */
?>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="<?= htmlspecialchars(
        $config["site"]["description"],
    ) ?>"/>
    <meta name="author" content="<?= htmlspecialchars(
        $config["site"]["author"],
    ) ?>"/>
    <title><?= htmlspecialchars(
        $page_title ?? $config["site"]["title"],
    ) ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <!-- Tailwind Config -->
    <script>
        // Theme Config
        const defaultTheme = {
            mode: 'dark', // 'light', 'dark', 'system'
            primary: '#2563eb' // Default Blue
        };

        // Load saved theme
        try {
            const saved = localStorage.getItem('theme_config');
            if (saved) {
                const parsed = JSON.parse(saved);
                window.themeConfig = { ...defaultTheme, ...parsed };
            } else {
                window.themeConfig = defaultTheme;
            }
        } catch (e) {
            window.themeConfig = defaultTheme;
        }

        // Apply Mode immediately to avoid flash
        if (window.themeConfig.mode === 'dark' || (window.themeConfig.mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        tailwind.config = {
            darkMode: "class",
            theme: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
                    display: ['Inter', 'sans-serif'],
                },
                extend: {
                    colors: {
                        // Dynamic Primary
                        primary: window.themeConfig.primary,
                        
                        // Zinc Palette (Premium Dark)
                        bg: '#09090b',      /* Zinc 950 */
                        surface: '#18181b', /* Zinc 900 */
                        border: '#27272a',  /* Zinc 800 */
                        text: '#fafafa',    /* Zinc 50 */
                        muted: '#a1a1aa',   /* Zinc 400 */
                        
                        // Legacy mappings for compatibility
                        "background-light": "#f6f6f8",
                        "background-dark": "#09090b",
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                },
            },
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #09090b; }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }
        
        body { 
            background-color: #f6f6f8; 
            color: #18181b;
        }
        .dark body {
            background-color: #09090b;
            color: #fafafa;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 20px;
        }
    </style>
</head>
