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
    <meta name="description" content="Ivan Santoso - Full Stack Developer & AI Specialist. Expert in PHP, PostgreSQL, and LLM Integration."/>
    <meta name="author" content="Ivan Santoso"/>
    <meta name="keywords" content="Full Stack Developer, AI Developer, LLM Integration, PHP, PostgreSQL, Web Developer"/>
    <title><?= htmlspecialchars(
        $page_title ?? $config["site"]["title"] ?? "Ivan Santoso | Full Stack Developer & AI Specialist",
    ) ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- FontAwesome for additional icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Fonts: Inter + Outfit for modern tech look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
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

        /* Modern Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, #4361ee, #4cc9f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(67, 97, 238, 0.2);
        }

        /* Hero Image Morph Animation */
        .hero-img {
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            animation: morph 8s ease-in-out infinite;
            border: 4px solid rgba(255,255,255,0.2);
        }
        @keyframes morph {
            0% { border-radius: 60% 40% 30% 70%/60% 30% 70% 40%; }
            50% { border-radius: 30% 60% 70% 40%/50% 60% 30% 60%; }
            100% { border-radius: 60% 40% 30% 70%/60% 30% 70% 40%; }
        }

        /* Skill Badges */
        .skill-badge {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            border-radius: 50px;
            background: rgba(67, 97, 238, 0.1);
            color: #4361ee;
            font-weight: 600;
            border: 1px solid rgba(67, 97, 238, 0.2);
            transition: all 0.3s;
            display: inline-block;
        }
        .skill-badge:hover {
            background: #4361ee;
            color: white;
        }
        .dark .skill-badge {
            background: rgba(67, 97, 238, 0.2);
            color: #a5b4fc;
        }
        .dark .skill-badge:hover {
            background: #4361ee;
            color: white;
        }

        /* AI Tag */
        .ai-tag {
            background: linear-gradient(135deg, #7209b7, #4361ee);
            color: white;
            padding: 0.35rem 0.85rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Progress Bars */
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            background: #27272a;
            overflow: hidden;
        }
        .progress-bar .fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, #4361ee, #4cc9f0);
            transition: width 1s ease;
        }

        /* Font Family Override */
        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Outfit', 'Inter', sans-serif;
        }
    </style>
</head>
