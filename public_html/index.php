<?php
/**
 * Front Controller
 * Handles all requests and routes to appropriate pages
 */

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set("display_errors", 0); // Set to 0 in production
ini_set("log_errors", 1);

// Security headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Type: text/html; charset=utf-8");

// Start output buffering
ob_start();

// Load configuration
$config = require __DIR__ . "/config.php";

// Determine current page from URL
$request_uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$current_page = trim($request_uri, "/");

// Map routes to page files
$routes = [
    "" => "home.php",
    "home" => "home.php",
    "experience" => "experience.php",
    "projects" => "projects.php",
    "skills" => "skills.php",
    "about" => "about.php",
    "resume" => "resume.php",
    "contact" => "contact.php",
];

// Default to home if route not found
if (!isset($routes[$current_page])) {
    http_response_code(404);
    $current_page = "404";
    $page_file = "pages/404.php";
} else {
    $page_file = "pages/" . $routes[$current_page];
}

// Page title
$page_title =
    $config["site"]["name"] . " - " . ucfirst($current_page ?: "Home");

// Render page
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<?php require __DIR__ . "/components/head.php"; ?>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">

        <?php require __DIR__ . "/components/navbar.php"; ?>

        <?php if (file_exists($page_file)) {
            require $page_file;
        } else {
            require "pages/home.php";
        } ?>

        <?php
// require __DIR__ . '/components/footer.php';
?>
    </div>

    <script>
        // Mobile menu toggle (enhanced UX)
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
            // Add mobile menu logic here
            console.log('Mobile menu toggled');
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>
