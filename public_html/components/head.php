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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400,500,700,900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "<?= $config["appearance"][
                            "primary_color"
                        ] ?>",
                        "background-light": "<?= $config["appearance"][
                            "background_light"
                        ] ?>",
                        "background-dark": "<?= $config["appearance"][
                            "background_dark"
                        ] ?>",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 20px;
        }
    </style>
</head>
