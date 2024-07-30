<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gosyen Team</title>
    <link rel="icon" href="https://gosyenpolinator.info/images/gosyen_logo.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        const sunIcon = document.querySelector('.sun');
        const moonIcon = document.querySelector('.moon');

        // Function to set the theme based on user preference or system preference
        const setTheme = () => {
            const userTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = userTheme || systemTheme;

            document.documentElement.classList.toggle('dark', theme === 'dark');
            if (theme === 'dark') {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        };

        // Function to toggle the theme and store user preference
        const toggleTheme = () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            setTheme();
        };

        // Attach event listeners
        const attachEventListeners = () => {
            if (!sunIcon || !moonIcon) return;
            sunIcon.removeEventListener('click', toggleTheme);
            moonIcon.removeEventListener('click', toggleTheme);

            sunIcon.addEventListener('click', toggleTheme);
            moonIcon.addEventListener('click', toggleTheme);
        };

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', () => {
            setTheme();
            attachEventListeners();
        });

        // Re-apply theme and attach listeners after Livewire updates
        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', () => {
                setTheme();
                attachEventListeners();
            });
        });

        // Re-apply theme and attach listeners after page navigation
        window.addEventListener('popstate', () => {
            setTheme();
            attachEventListeners();
        });
    </script>

</body>

</html>
