<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFTPLAY</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Estilos embebidos -->
    <style>
        :root {
            --transition-speed: 0.3s;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            transition: all var(--transition-speed) ease;
            min-height: 100vh;
            position: relative;
            padding-bottom: 60px;
        }

        body.dark-mode {
            background-color: #1a1d21;
            color: #e9ecef;
        }

        body.dark-mode .navbar {
            background-color: #242832 !important;
            border-bottom: 1px solid #2d3139;
        }

        body.dark-mode .navbar-brand,
        body.dark-mode .nav-link {
            color: #e9ecef !important;
        }

        body.dark-mode .card {
            background-color: #242832;
            border-color: #2d3139;
        }

        .theme-controls {
            position: fixed;
            top: 200px;
            right: 40px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            backdrop-filter: blur(10px);
            z-index: 1050;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
            transition: all var(--transition-speed) ease;
        }

        body.dark-mode .theme-controls {
            background: rgba(36, 40, 50, 0.9);
        }

        .background-selector {
            display: flex;
            gap: 10px;
            margin: 0;
        }

        .background-option {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all var(--transition-speed) ease;
            position: relative;
        }

        .background-option:hover {
            transform: scale(1.15);
        }

        .background-option.selected {
            border-color: #0d6efd;
            transform: scale(1.1);
        }

        .background-option.selected::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            text-shadow: 0 0 2px rgba(0,0,0,0.5);
        }

        .navbar {
            padding: 1rem 0;
            transition: all var(--transition-speed) ease;
            box-shadow: var(--box-shadow);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 1.8rem;
        }

        #theme-toggle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #0d6efd;
            color: white;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            box-shadow: var(--box-shadow);
        }

        #theme-toggle:hover {
            transform: scale(1.1);
            background: #0b5ed7;
        }

        #theme-toggle i {
            font-size: 1.4rem;
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: all var(--transition-speed) ease;
            border: none;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #0d6efd, #dc3545);
            z-index: 9999;
            animation: loading 2s ease-in-out infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="loader"></div>
        <button id="theme-toggle" aria-label="Toggle Theme">
            <i id="theme-icon" class="bi bi-sun-fill"></i>
        </button>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-boxes"></i> SOFTPLAY
            </a>
        </div>
    </nav>

    <main class="py-4 fade-in">
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Tema -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const backgroundOptions = document.querySelectorAll('.background-option');
            const loader = document.querySelector('.loader');

            window.addEventListener('load', () => {
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            });

            themeToggle.addEventListener('click', function () {
                body.classList.toggle('dark-mode');
                const isDark = body.classList.contains('dark-mode');
                themeIcon.className = isDark ? 'bi bi-moon-stars-fill' : 'bi bi-sun-fill';
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });

            backgroundOptions.forEach(option => {
                option.addEventListener('click', function () {
                    backgroundOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    const bgColor = this.dataset.bg;
                    body.style.backgroundColor = bgColor;
                    localStorage.setItem('background', bgColor);
                });

                option.addEventListener('mouseover', function () {
                    if (!this.classList.contains('selected')) {
                        this.style.transform = 'scale(1.15)';
                    }
                });

                option.addEventListener('mouseout', function () {
                    if (!this.classList.contains('selected')) {
                        this.style.transform = 'scale(1)';
                    }
                });
            });

            // Cargar ajustes guardados
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark-mode');
                themeIcon.className = 'bi bi-moon-stars-fill';
            }

            const savedBackground = localStorage.getItem('background');
            if (savedBackground) {
                body.style.backgroundColor = savedBackground;
                const selectedOption = Array.from(backgroundOptions).find(opt => opt.dataset.bg === savedBackground);
                if (selectedOption) selectedOption.classList.add('selected');
            } else {
                backgroundOptions[0].classList.add('selected');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>