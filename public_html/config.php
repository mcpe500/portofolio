<?php
/**
 * Site Configuration
 * Centralized data for maintainability
 */

return [
    "site" => [
        "name" => "Ivan Santoso",
        "title" => "Ivan Santoso - Personal Portfolio",
        "author" => "Ivan Santoso",
        "description" =>
            "Computer Science Student & Aspiring Software Developer",
        "bio" =>
            "As a computer science student at Institut Sains dan Teknologi Terpadu Surabaya, I have developed a deep interest in programming and its potential to shape the future. My passion lies in developing innovative solutions that solve real-world problems and create meaningful change.",
    ],

    "navigation" => [
        ["label" => "Experience", "url" => "/experience", "type" => "link"],
        ["label" => "Projects", "url" => "/projects", "type" => "link"],
        ["label" => "Tools", "url" => "/tools", "type" => "link"],
        ["label" => "Games", "url" => "/games", "type" => "link"],
        ["label" => "Skills", "url" => "/skills", "type" => "link"],
        ["label" => "Education", "url" => "/education", "type" => "link"],
        ["label" => "About", "url" => "/about", "type" => "link"],
        ["label" => "Contact", "url" => "/contact", "type" => "button"],
    ],

    "social_links" => [
        [
            "label" => "GitHub",
            "icon" => "code_blocks",
            "url" => "#",
            "title" => "View my GitHub profile",
        ],
        [
            "label" => "LinkedIn",
            "icon" => "business_center",
            "url" => "#",
            "title" => "Connect on LinkedIn",
        ],
        [
            "label" => "Email",
            "icon" => "mail",
            "url" => "#",
            "title" => "Send me an email",
        ],
    ],

    "experience" => [
        [
            "title" => "Software Engineer Internship",
            "company" => "PT HM Sampoerna Tbk. (Philip Morris International)",
            "date" => "Feb 2025 - Present · 10 mos",
            "location" => "Surabaya, East Java, Indonesia · On-site",
            "description" => [
                "Developing and maintaining enterprise-level internal applications",
                "Participating in Agile development lifecycle and code reviews",
                "Writing unit tests to maintain high-quality code standards",
            ],
            "skills" => [
                "Agile",
                "Unit Testing",
                "Node.js",
                "PHP",
                "Laravel",
                "Python",
                "AWS",
                "Linux",
                "Docker",
            ],
        ],
        [
            "title" => "Assistant Lecturer",
            "company" =>
                "Institut Sains dan Teknologi Terpadu Surabaya (iSTTS)",
            "date" => "Mar 2023 - Feb 2025 · 2 yrs",
            "location" => "Surabaya",
            "description" => [
                "Mentored over 50 students in fundamental programming concepts",
                "Prepared and delivered tutorial sessions for complex topics",
                "Assisted in grading assignments and providing constructive feedback",
            ],
            "skills" => [
                "C++",
                "Java",
                "Python",
                "Teaching",
                "Mentorship",
                "Curriculum Development",
            ],
        ],
        [
            "title" => "Classroom Assistant",
            "company" =>
                "Institut Sains dan Teknologi Terpadu Surabaya (iSTTS)",
            "date" => "Sep 2024 - Jan 2025 · 5 mos",
            "location" => "Kota Surabaya, East Java, Indonesia",
            "description" => ["Taught algorithm concepts to students."],
            "skills" => ["Algorithms", "Teaching"],
        ],
        [
            "title" => "Frontend Developer",
            "company" => "EClub Indonesia · Freelance",
            "date" => "Sep 2024 - Dec 2024 · 4 mos",
            "location" => "Indonesia · Remote",
            "description" => [
                "Developed responsive web interfaces for client projects",
                "Collaborated with design team to implement UI/UX improvements",
                "Optimized web performance and cross-browser compatibility",
            ],
            "skills" => [
                "Next.js",
                "React.js",
                "tRPC",
                "Mantine",
                "Tailwind CSS",
                "JavaScript",
                "Responsive Design",
            ],
        ],
        [
            "title" => "Classroom Assistant",
            "company" =>
                "Institut Sains dan Teknologi Terpadu Surabaya (iSTTS)",
            "date" => "Sep 2023 - Jun 2024 · 10 mos",
            "location" => "Indonesia",
            "description" => ["Assisted in teaching and mentoring students."],
            "skills" => ["Teaching"],
        ],
    ],
    "appearance" => [
        "primary_color" => "#135bec",
        "background_light" => "#f6f6f8",
        "background_dark" => "#101622",
    ],
    "tools" => [
        "mermaid" => [
            "title" => "Mermaid Editor",
            "description" => "Create diagrams and charts using text and code.",
            "icon" => "account_tree",
            "route" => "tools/mermaid",
            "status" => "stable",
        ],
        "html" => [
            "title" => "HTML Preview",
            "description" => "Real-time HTML/CSS editor with fullscreen preview.",
            "icon" => "html",
            "route" => "tools/html",
            "status" => "stable",
        ],
        "markdown" => [
            "title" => "Markdown Editor",
            "description" => "Write Markdown with live HTML preview.",
            "icon" => "markdown",
            "route" => "tools/markdown",
            "status" => "stable",
        ],
        "react" => [
            "title" => "React Playground",
            "description" => "Experiment with React components in the browser.",
            "icon" => "code_blocks",
            "route" => "tools/react",
            "status" => "experimental",
        ],
        "vue" => [
            "title" => "Vue Playground",
            "description" => "Live Vue.js component editor.",
            "icon" => "view_quilt",
            "route" => "tools/vue",
            "status" => "experimental",
        ],
        "angular" => [
            "title" => "AngularJS Playground",
            "description" => "Legacy AngularJS (v1.x) playground.",
            "icon" => "javascript",
            "route" => "tools/angular",
            "status" => "legacy",
        ],
        "svelte" => [
            "title" => "Svelte Playground",
            "description" => "Experimental Svelte component viewer.",
            "icon" => "bolt",
            "route" => "tools/svelte",
            "status" => "experimental",
        ],
        "swagger" => [
            "title" => "Swagger Editor",
            "description" => "Design and document APIs with OpenAPI.",
            "icon" => "api",
            "route" => "tools/swagger",
            "status" => "experimental",
        ],
        "nodejs" => [
            "title" => "Node.js Playground",
            "description" => "Run JavaScript with console emulation.",
            "icon" => "terminal",
            "route" => "tools/nodejs",
            "status" => "stable",
        ],
        "typescript" => [
            "title" => "TypeScript Playground",
            "description" => "Compile and run TypeScript in the browser.",
            "icon" => "code",
            "route" => "tools/typescript",
            "status" => "stable",
        ],
        "coffeescript" => [
            "title" => "CoffeeScript Playground",
            "description" => "Compile and run CoffeeScript.",
            "icon" => "coffee",
            "route" => "tools/coffeescript",
            "status" => "legacy",
        ],
        "vbscript" => [
            "title" => "VBScript Editor",
            "description" => "VBScript editor with syntax highlighting and HTML preview.",
            "icon" => "description",
            "route" => "tools/vbscript",
            "status" => "legacy",
        ],

        // NEW: QR Generator
        "qr-generator" => [
            "title" => "QR Code Generator",
            "description" => "Generate QR codes for text, URLs, and more.",
            "icon" => "qr_code_2",
            "route" => "tools/qr",
            "status" => "stable",
        ],

        // NEW: Barcode Generator
        "barcode-generator" => [
            "title" => "Barcode Generator",
            "description" => "Create common 1D barcodes (CODE128, EAN-13, etc.).",
            "icon" => "barcode",
            "route" => "tools/barcode",
            "status" => "stable",
        ],

        // NEW: GLB Viewer
        "glb-viewer" => [
            "title" => "GLB Viewer",
            "description" => "Preview local .glb 3D models in the browser.",
            "icon" => "view_in_ar",
            "route" => "tools/glb-viewer",
            "status" => "experimental",
        ],

        // NEW: GLTF Viewer
        "gltf-viewer" => [
            "title" => "GLTF Viewer",
            "description" => "Load and inspect .gltf models from a URL.",
            "icon" => "view_in_ar",
            "route" => "tools/gltf-viewer",
            "status" => "experimental",
        ],

        // NEW: REST Client
        "rest-client" => [
            "title" => "REST Client",
            "description" => "Full-featured API testing tool like Postman",
            "icon" => "api",
            "route" => "tools/rest-client",
            "status" => "stable",
        ],

        // NEW: Data Converter
        "data-converter" => [
            "title" => "Data Converter",
            "description" => "Convert between JSON, YAML, TOML, XML formats",
            "icon" => "transform",
            "route" => "tools/data-converter",
            "status" => "stable",
        ],

        // NEW: Diff Checker
        "diff-checker" => [
            "title" => "Diff Checker",
            "description" => "Compare text and files with advanced diff visualization",
            "icon" => "difference",
            "route" => "tools/diff",
            "status" => "stable",
        ],

        // NEW: Regex Tester
        "regex-tester" => [
            "title" => "Regex Tester",
            "description" => "Test regular expressions with live highlighting",
            "icon" => "regular_expression",
            "route" => "tools/regex",
            "status" => "stable",
        ],

        // NEW: JWT Decoder
        "jwt-decoder" => [
            "title" => "JWT Decoder",
            "description" => "Decode and inspect JWT tokens",
            "icon" => "key",
            "route" => "tools/jwt",
            "status" => "stable",
        ],

        // NEW: Date Formatter
        "date-formatter" => [
            "title" => "Date Formatter",
            "description" => "Convert dates between various formats (ISO 8601, RFC 3339, Unix, etc.)",
            "icon" => "calendar_today",
            "route" => "tools/date-formatter",
            "status" => "stable",
        ],

        // NEW: Password Generator
        "password-generator" => [
            "title" => "Password Generator",
            "description" => "Generate secure, random passwords and strings with custom options.",
            "icon" => "lock_reset",
            "route" => "tools/password-generator",
            "status" => "stable",
        ],

        // NEW: Hash & Encryption
        "hash-crypto" => [
            "title" => "Hash & Encryption",
            "description" => "Compute hashes (MD5, SHA) and encrypt/decrypt text (AES, DES).",
            "icon" => "enhanced_encryption",
            "route" => "tools/hash-crypto",
            "status" => "stable",
        ],

        // NEW: PDF Converter
        "pdf-converter" => [
            "title" => "PDF Converter",
            "description" => "Convert Images, HTML, and Documents to PDF.",
            "icon" => "picture_as_pdf",
            "route" => "tools/pdf-converter",
            "status" => "beta",
        ],

        // NEW: PDF Merger
        "pdf-merger" => [
            "title" => "PDF Merger",
            "description" => "Merge multiple PDF files into a single document.",
            "icon" => "picture_as_pdf",
            "route" => "tools/pdf-merger",
            "status" => "beta",
        ],

        // NEW: Command Helper
        "command-helper" => [
            "title" => "Command Helper",
            "description" => "Generators for Cron jobs, Chmod permissions, and Tar commands.",
            "icon" => "terminal",
            "route" => "tools/command-helper",
            "status" => "stable",
        ],

        // NEW: Quantum Visualizer
        "quantum" => [
            "title" => "Quantum Visualizer",
            "description" => "Interactive Quantum Circuit Simulator with Math & Physics.",
            "icon" => "science",
            "route" => "tools/quantum",
            "status" => "experimental",
        ],

        "calc-scientific" => [
            "title" => "Scientific Calculator",
            "description" => "Advanced math with functions and history.",
            "icon" => "calculate",
            "route" => "tools/calc/scientific",
            "status" => "stable",
        ],
        "calc-graphing" => [
            "title" => "Graphing Calculator",
            "description" => "Plot 2D functions and visualize data.",
            "icon" => "monitoring",
            "route" => "tools/calc/graphing",
            "status" => "stable",
        ],
        "calc-converter" => [
            "title" => "Unit Converter",
            "description" => "Convert Length, Mass, Volume, and more.",
            "icon" => "straighten",
            "route" => "tools/calc/converter",
            "status" => "stable",
        ],
        "calc-finance" => [
            "title" => "Finance Calculator",
            "description" => "Compound Interest, ROI, and Growth Charts.",
            "icon" => "payments",
            "route" => "tools/calc/finance",
            "status" => "stable",
        ],
        "calc-programmer" => [
            "title" => "Programmer Calculator",
            "description" => "HEX, DEC, OCT, BIN and Bitwise Operations.",
            "icon" => "terminal",
            "route" => "tools/calc/programmer",
            "status" => "stable",
        ],
    ],
    "games" => [
        "uno" => [
            "title" => "UNO Multiplayer",
            "description" => "Classic UNO card game. Create a room and play with friends online!",
            "icon" => "playing_cards",
            "route" => "games/uno",
            "status" => "new",
        ],
        "monopoly" => [
            "title" => "Monopoly Multiplayer",
            "description" => "Classic board game. Buy properties, collect rent, and bankrupt your friends!",
            "icon" => "casino",
            "route" => "games/monopoly",
            "status" => "new",
        ],
    ],
    "projects" => [
        [
            "title" => "Rupavo",
            "description" =>
                "A smart commerce platform for MSMEs that aids in daily business performance analysis. Automated catalog generation, sales reporting, and AI-driven insights.",
            "image" => "assets/img/rupavo-storefront.png",
            "alt" => "Rupavo Storefront Dashboard",
            "skills" => ["Next.js", "React", "Tailwind CSS", "AI", "Android"],
            "date" => "Present",
            "links" => [
                [
                    "type" => "demo",
                    "url" => "https://rupavo-storefront.vercel.app/",
                    "label" => "Storefront",
                ],
                [
                    "type" => "demo",
                    "url" => "https://rupavo-principal.vercel.app/",
                    "label" => "Principal",
                ],
                [
                    "type" => "github",
                    "url" => "https://github.com/mcpe500/rupavo-repo",
                    "label" => "GitHub",
                ],
                [
                    "type" => "demo",
                    "url" => "https://github.com/mcpe500/rupavo-repo/releases/tag/MVP",
                    "label" => "Merchant App",
                ],
            ],
        ],
        [
            "title" => "Pocket Tracer",
            "description" =>
                "A Flutter-based mobile application designed for tracking personal finances. Helps users manage income and expenses to understand spending patterns and improve financial health.",
            "image" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuCIeNJ81rsLSzfx8maR8fAaa9BuCU9f9FywOXNK0bqWrZHytJOjIIGX5UnZENbp_jG1VT5xXpxRYQ918RWRbfvwijhbiWvUoyMrMNO9BJFct6WqrypTtS21wEFzuwrBz1yWJoOpnQecuyrnanDwSqxk_fP7rndYETHPyDgvAOuvYd0teuVifze5sQ2Wq7z6KfzZCG60i_yGe86YorHQV67ec7GG5xV4v_wnnf-9PWVbxqBVfU0JOoNjvNRDGREJdbR03AUV5mpXXwM",
            "alt" =>
                "A smartphone screen showing a budget tracking application interface with charts and expense lists.",
            "skills" => ["Flutter", "Dart", "Firebase"],
            "date" => "Oct 2024 - Present",
            "links" => [
                [
                    "type" => "github",
                    "url" => "https://github.com/mcpe500/pocket-tracer",
                    "label" => "GitHub",
                ],
            ],
        ],
        [
            "title" => "note-writing-app",
            "description" =>
                "A Flutter prototype for a mobile writing app, allowing users to create and edit text content. Final project for Basic Flutter Certification through Dicoding.",
            "image" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuAPrqNfuz6A427iwLEEzbhg44g78TyfzvOb9ATJMDf9svLvYFj-i0Pl1s6Tvc7V7XIW6iadC_-FiCJM7d_MC7fn0C0u6LYz0Q9X1olmidn-jlDChikgFI496FJK5cNy9gaROyuP61sl0rPzzJC-wJRAaHADCScdz3lMrGmLwaig7wSjTVQm5rkzLlCbDaJ5NGBwZSCwnMC7dE63XQRAjDDAXGNPSPJyHrOpgcF8TWUOPlRI6NXQYK2SCoguTXlCkEstlxRrObpUj6Q",
            "alt" =>
                "A clean and minimal mobile writing application interface.",
            "skills" => ["Flutter", "Dart"],
            "date" => "May 2024 - Present",
            "links" => [
                [
                    "type" => "github",
                    "url" => "https://github.com/mcpe500/note-writing-app",
                    "label" => "GitHub",
                ],
            ],
        ],
        [
            "title" => "Final Project: 3D Graphics",
            "description" =>
                "Interactive 3D graphics application using Three.js with features like instanced meshes and water simulations. Collaborative final project for computer graphics course.",
            "image" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuDo0zpTukEoJ2iXO-8Bb6C1Wuc1RwxCk8h3hjCq8Jo7XfiGBLiRvtGeMuBBwWMuyJlftEc0wKel-TEG1XjmmXFWIX69Av39f874mkbI7Csg-YD1uOgnvdPzK4-UQcGlnlBSvuuE5d7Nj_RkuCLU7F4l-AtXY5Mkp7qv4_bjgFHKjFC7E5A9CUjuYSiODvypRVnBBDpKrmyo0fAZFq_kJOYfhD3pbrWus0gdzMaRkzBu5GQVtbbO84ckRFFRW8sOe_sFhpiBsLxGUe4",
            "alt" =>
                "An abstract, colorful 3D wireframe graphic representing a WebGL project.",
            "skills" => [
                "Three.js",
                "JavaScript",
                "WebGL",
                "Computer Graphics",
            ],
            "date" => "Apr 2024 - Jun 2024",
            "links" => [
                [
                    "type" => "demo",
                    "url" => "https://mcpe500.github.io/proyek_grafkom/",
                    "label" => "Live Demo",
                ],
                [
                    "type" => "github",
                    "url" => "https://github.com/mcpe500/proyek_grafkom",
                    "label" => "GitHub",
                ],
            ],
        ],
        [
            "title" => "WeFit",
            "description" =>
                "Collaborative social fitness application for Android allowing users to track workouts, set goals, and share progress with friends.",
            "image" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuAPi6npLV44JAxAgfrYh2DZvBTQsCaHPomG5GkjRs_Jeso895oTAwpbdX6bzMz1y065axDRCHxMHagY4DJhrP2GeMqjYc2tvUIEGIeygCz66WHnBkXCc-DxJfiGonrrZM9IQ3uZgfYS-F6KSlxq6-9CKNPX4lGRvaxLnPpZOxntxIIHl1kGuSVuZ3pWJ885M9r2nd6xHrsebwq2raUkpoyqHJLkpxiI82fShUVRbHsA9tyXLbEnHN3ByqgOi2gbIfv3tf-RNIQqJ7M",
            "alt" =>
                "A mobile phone displaying a fitness application dashboard with activity rings and workout stats.",
            "skills" => ["Java", "Android Studio", "SQL", "Google Maps"],
            "date" => "Apr 2024 - Jun 2024",
            "links" => [
                [
                    "type" => "github",
                    "url" => "https://github.com/mcpe500/Proyek_WS",
                    "label" => "GitHub",
                ],
            ],
        ],
    ],
    "skills" => [
        "Web Development" => [
            [
                "name" => "React.js",
                "endorsements" => 2,
                "logo" => "react",
                "source" => "React JS - Web Frontend Development",
            ],
            [
                "name" => "Node.js",
                "endorsements" => 2,
                "logo" => "node",
                "source" => "React JS - Web Frontend Development",
            ],
            [
                "name" => "Laravel",
                "endorsements" => 0,
                "logo" => "laravel",
                "source" => "Laravel Web Development",
            ],
            [
                "name" => "JavaScript",
                "endorsements" => 10,
                "logo" => "js",
                "source" => "Multiple certifications",
            ],
            [
                "name" => "TypeScript",
                "endorsements" => 0,
                "logo" => "ts",
                "source" => "WeFit project",
            ],
        ],
        "Mobile Development" => [
            [
                "name" => "Flutter",
                "endorsements" => 2,
                "logo" => "flutter",
                "source" => "Belajar Membuat Aplikasi Flutter",
            ],
            [
                "name" => "Dart",
                "endorsements" => 0,
                "logo" => "dart",
                "source" => "Pocket Tracer",
            ],
            [
                "name" => "Kotlin",
                "endorsements" => 2,
                "logo" => "kotlin",
                "source" => "Android Development",
            ],
            [
                "name" => "Android Studio",
                "endorsements" => 2,
                "logo" => "android",
                "source" => "Android Development",
            ],
        ],
        "Data & Machine Learning" => [
            [
                "name" => "Python",
                "endorsements" => 2,
                "logo" => "python",
                "source" => "Multiple certifications",
            ],
            [
                "name" => "Machine Learning",
                "endorsements" => 4,
                "logo" => "ml",
                "source" => "Machine Learning Developer",
            ],
            [
                "name" => "Pandas",
                "endorsements" => 2,
                "logo" => "pandas",
                "source" => "Pandas certification",
            ],
            [
                "name" => "Data Analysis",
                "endorsements" => 2,
                "logo" => "analysis",
                "source" => "Data Analysis certifications",
            ],
        ],
        "DevOps & Tools" => [
            [
                "name" => "Docker",
                "endorsements" => 3,
                "logo" => "docker",
                "source" => "Red Hat Containers",
            ],
            [
                "name" => "CI/CD",
                "endorsements" => 0,
                "logo" => "cicd",
                "source" => "WeFit project",
            ],
            [
                "name" => "Linux",
                "endorsements" => 0,
                "logo" => "linux",
                "source" => "WeFit project",
            ],
            [
                "name" => "Git",
                "endorsements" => 0,
                "logo" => "git",
                "source" => "Project experience",
            ],
        ],
        "Interpersonal & Other" => [
            [
                "name" => "Teaching",
                "endorsements" => 1,
                "logo" => "teach",
                "source" => "Classroom Assistant",
            ],
            [
                "name" => "Team Leadership",
                "endorsements" => 1,
                "logo" => "lead",
                "source" => "WeFit project",
            ],
            [
                "name" => "Teamwork",
                "endorsements" => 1,
                "logo" => "team",
                "source" => "Multiple projects",
            ],
        ],
    ],

    "certifications" => [
        [
            "title" => "React JS - Web Frontend Development",
            "issuer" => "SanberCode",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuAa1TUz3FvcfmJS7A1PW0pCSnJ1EEbXg2UuEjDK9NRH8aahVeGB8K9TpemrocoLYEf7pNuLZEKbVksZq5Q1AsKp9iBUy6mBVJy-4qcjGkgdk4GxlCgpH-lnq8RBvMxk69Xf9BH8CJIG-OdEB3_AyypLlN-pMk6fB5REq94aDEzjcyK1rxmu9B3YrKv_DbzRie0ijzp0n2ZLRcWFrRqqkE61eu-aeAOjE0noRpDQqwY9Y7lLiLzIRGBwoIsC4grtn90hZ0XCHtrfdok",
            "date" => "Issued Apr 2025",
            "credential_id" => "8eed9b93-b23e-46e8-906e-6249c62c307e",
            "category" => "Web Dev",
            "skills" => ["React.js", "Node.js"],
            "url" => "#",
        ],
        [
            "title" => "Laravel Web Development",
            "issuer" => "SanberCode",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuAa1TUz3FvcfmJS7A1PW0pCSnJ1EEbXg2UuEjDK9NRH8aahVeGB8K9TpemrocoLYEf7pNuLZEKbVksZq5Q1AsKp9iBUy6mBVJy-4qcjGkgdk4GxlCgpH-lnq8RBvMxk69Xf9BH8CJIG-OdEB3_AyypLlN-pMk6fB5REq94aDEzjcyK1rxmu9B3YrKv_DbzRie0ijzp0n2ZLRcWFrRqqkE61eu-aeAOjE0noRpDQqwY9Y7lLiLzIRGBwoIsC4grtn90hZ0XCHtrfdok",
            "date" => "Issued Feb 2025",
            "credential_id" => "90e91960-193d-40e0-b5e6-f209444d2874",
            "category" => "Web Dev",
            "skills" => ["Laravel"],
            "url" => "#",
        ],
        [
            "title" => "Building Real-Time Video AI Applications",
            "issuer" => "NVIDIA",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuDq9YYZwBYRd1G0gIRvHbs1LNOh9RvgwnapI5acacW41QSzAXkef090sTIgdOLyZTg5UUErJYHqKFEP9hymKRuCgB5ns_Q12r27hV4_GRmYmHZR4zmY4gAaMmUhMcqxDYyeBWV5qYmZ92YykiVY0L35qqYkQCkkP69Tvd7HXm8gzVCfnoRxAR0laz1IxCBqSt6wquatH-wHHOl5aI2ZqhqpoFTXomEaln70MlHScwxAgBTc0uuyN279Zl22-x5YXaTAEbXNzG7ZU4I",
            "date" => "Issued Dec 2024",
            "credential_id" => "Q4EMbY4-RIC3lBbVLPFPYQ",
            "category" => "AI/ML",
            "skills" => ["Computer Vision", "AI"],
            "url" => "#",
        ],
        [
            "title" => "Prompt Engineer",
            "issuer" => "SanberCode",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuAa1TUz3FvcfmJS7A1PW0pCSnJ1EEbXg2UuEjDK9NRH8aahVeGB8K9TpemrocoLYEf7pNuLZEKbVksZq5Q1AsKp9iBUy6mBVJy-4qcjGkgdk4GxlCgpH-lnq8RBvMxk69Xf9BH8CJIG-OdEB3_AyypLlN-pMk6fB5REq94aDEzjcyK1rxmu9B3YrKv_DbzRie0ijzp0n2ZLRcWFrRqqkE61eu-aeAOjE0noRpDQqwY9Y7lLiLzIRGBwoIsC4grtn90hZ0XCHtrfdok",
            "date" => "Issued Dec 2024",
            "credential_id" => "43df8ec4-0b8a-4c3c-b3f7-51e783d96cd2",
            "category" => "AI/ML",
            "skills" => ["Prompt Engineering"],
            "url" => "#",
        ],
        [
            "title" => "Belajar Membuat Aplikasi Flutter untuk Pemula",
            "issuer" => "Dicoding Indonesia",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuB5-qhfbcP5J0lbo0NhnfvXLzRHqgwy-puSU8MHXVLXx636e7G8oE1nv7nkhoaz3UnIHXIc0Nsi2J5Ip4oCTqMwdo-hQIYFnQFuDzrBgaEy3UIYbGL-R6D5y-MUkphWcOFDZoNooE65CZBtKOdTjTthb3_fhNNkMrAhYEAJeYNH2HrLGpP7EZB6rTYb9n9CYTEtal1RY-AAP_H4VcJvIZTJpvnJ6l3zc32gWxltEmTBje_S67NzkNy1_PLpGljR9ySI9TQQbAZuerM",
            "date" => "Issued May 2024 · Expires May 2027",
            "credential_id" => "98XWL280WZM3",
            "category" => "Mobile",
            "skills" => ["Flutter"],
            "url" => "#",
        ],
        [
            "title" => "JavaScript Algorithms and Data Structures",
            "issuer" => "freeCodeCamp",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuBWHD1dxuPgGKY18UP3F8Msl5PTw0KUKpR_w5COMWZt_W5TDzmuzunNvtZicPXSg8wscb0IIyLmitYwZ3gbfaUm3fp3wPGjVs5TGtvWa_ClgIlH_rbLjJEXKA0Rr8S_uLZ5BDaJAg6AO-nM3-c3fJXxz616SyyL0I8HrlOSwq27apt2w4g7et4vwkuDZI8ioarAq8gXJL0vUqRnzVSATAbf4UK7UdTGtOOSD5cnBjlnuNU8JetQ1DHR5o-jtMZqx65OuUA3DjZiUso",
            "date" => "Issued Apr 2022",
            "credential_id" => "fcc-js-algorithms",
            "category" => "Web Dev",
            "skills" => ["JavaScript", "Data Structures", "Algorithms"],
            "url" => "#",
        ],
        [
            "title" => "AWS Certified Cloud Practitioner",
            "issuer" => "AWS",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuD6ryMiBv7FhXs71z4ZLJy1IZ-aW79JmK_VJfDns0SDDOuG-kpL_RoMHbNAu1WOx3uSf-viPbiCYhBXSwNguKbE91V1nlQZxCaMSXD81dEwZrcvYQ1X4AY5-WiobJcGC1Pwa-_9ZZbP_Evk0dD-TqFiOd02-J_Ioe9br-6vDRAB4ysh5ys2vBSIxExthOsdOczYPROjE0GzU2yGjxj8jRP_L75jbf9dAqaGr2_RCfGN43_QYfP2u15Ry2-uWjzJNMPulZmrI1jBzmg",
            "date" => "Issued Dec 2023",
            "credential_id" => "9c19e85c-fece-4ea6-9385-a8e21e291239",
            "category" => "Cloud",
            "skills" => ["AWS", "Cloud Computing"],
            "url" => "https://www.credly.com/badges/9c19e85c-fece-4ea6-9385-a8e21e291239/linked_in_profile",
        ],
        [
            "title" => "Red Hat Certified Specialist in Containers",
            "issuer" => "Red Hat",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuDFhXm3GrvIzFz7yJ4Zsgc4--AHtWz1Mnod7k3QjqQlb-Py0C5f61C0Ti-2smT1AYpATzQFx81BXwE_2nC60mjEPyuGfAg8far8_NlwmjplHUTPpaFiSbGkNNwAppUulVEz6Iasji3336DRt2pqNU-YMgStgIoTghDeAS0ibhflSZmqBBQZZFc22M8PzgWlPWvjwPmVecTNOQXwJUiMI2Uf-njeUkhCm57tGzuts0QGqKPZzcgOdbVToaHG7s-bw1O5YKJAoyvkU1M",
            "date" => "Issued Dec 2023 · Expires Dec 2026",
            "credential_id" => "d1b29e51-7f49-470d-9e55-be31937e3385",
            "category" => "DevOps",
            "skills" => ["Docker", "Containerization", "Podman"],
            "url" => "https://www.credly.com/badges/d1b29e51-7f49-470d-9e55-be31937e3385/linked_in_profile",
        ],
        [
            "title" => "Fundamentals of Deep Learning",
            "issuer" => "NVIDIA",
            "issuer_logo" =>
                "https://lh3.googleusercontent.com/aida-public/AB6AXuDq9YYZwBYRd1G0gIRvHbs1LNOh9RvgwnapI5acacW41QSzAXkef090sTIgdOLyZTg5UUErJYHqKFEP9hymKRuCgB5ns_Q12r27hV4_GRmYmHZR4zmY4gAaMmUhMcqxDYyeBWV5qYmZ92YykiVY0L35qqYkQCkkP69Tvd7HXm8gzVCfnoRxAR0laz1IxCBqSt6wquatH-wHHOl5aI2ZqhqpoFTXomEaln70MlHScwxAgBTc0uuyN279Zl22-x5YXaTAEbXNzG7ZU4I",
            "date" => "Issued Nov 2023",
            "credential_id" => "rFdGGj_oRd2NQbQglUJ1Bw",
            "category" => "AI/ML",
            "skills" => ["Deep Learning", "Neural Networks", "AI"],
            "url" => "https://learn.nvidia.com/certificates?id=0a554ebf268c414cbedb4332167edebe",
        ],
    ],
    "education" => [
        "institution" => "Sekolah Tinggi Teknik Surabaya",
        "degree" => "Bachelor of Technology in Informatics",
        "period" => "Aug 2022 - Aug 2026 (Expected)",
        "gpa" => "3.8/4.0",
        "achievements" => [
            'Dean\'s List for Academic Excellence (2023, 2024)',
            "Maintained a strong academic record with a GPA of 3.8/4.0",
            'Lead Developer for final year project on "AI-Powered Recommendation Engine"',
            'Consistently featured on the Dean\'s List for academic excellence',
        ],
        "semesters" => [
            [
                "number" => 1,
                "name" => "Introduction to Programming",
                "courses" => ["HTML/CSS/JavaScript", "C++"],
                "description" =>
                    "Foundation in web technologies and object-oriented programming with C++.",
            ],
            [
                "number" => 2,
                "name" => "Database & Object-Oriented Programming",
                "courses" => ["Java", "MySQL"],
                "description" =>
                    "Core Java programming and relational database management.",
            ],
            [
                "number" => 3,
                "name" => "Web Frameworks & Mobile Basics",
                "courses" => ["C#", "PHP Laravel"],
                "description" =>
                    "Backend development with C# and modern PHP frameworks.",
            ],
            [
                "number" => 4,
                "name" => "Advanced Web & Systems",
                "courses" => [
                    "Node.js/Express.js",
                    "Three.js",
                    "Digital Systems (https://github.com/hneemann/Digital)",
                ],
                "description" =>
                    "Full-stack JavaScript, 3D graphics, and digital logic simulation.",
            ],
            [
                "number" => 5,
                "name" => "Full-Stack Development",
                "courses" => [
                    "React.js + Express.js Fullstack",
                    "Nest.js (Backend) with EJS Admin Panel",
                    "Next.js Frontend (React TSX + tRPC hitting NestJS backend)",
                ],
                "description" =>
                    "Enterprise-level full-stack architecture with TypeScript and modern frameworks.",
            ],
            [
                "number" => 6,
                "name" => "Mobile & IoT",
                "courses" => ["Kotlin", "IoT with Kotlin Android", "ESP32"],
                "description" =>
                    "Mobile development and Internet of Things integration.",
            ],
            [
                "number" => 7,
                "name" => "Cross-Platform Development (Current)",
                "courses" => ["Flutter Multiplatform"],
                "description" =>
                    "Building cross-platform mobile applications with a single codebase.",
            ],
        ],
    ],
];
