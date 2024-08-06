<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome to Gosyen Employee System</title>
    <link rel="icon" href="https://gosyenpolinator.info/images/gosyen_logo.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

</head>

<body class="antialiased font-sans">
    <div class="relative text-black dark:text-white">
        <div class="relative bg-cover bg-center" style="background-image: url('/gosyen_asset/brown_desk.jpg');">

            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative bg-gray-900 w-10/12 md:w-1/2 max-w-2xl px-6 lg:max-w-7xl">
                    <header class="flex flex-col items-center items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex justify-center lg:col-start-2">
                            <image src="https://gosyenpolinator.info/images/gosyen_logo.png" class="w-20 fill-current">
                        </div>
                        <div class="mb-4 flex justify-center lg:col-start-2">
                            <p class="text-2xl text-center text-gray-400 italic">Welcome to Gosyen Employee System</p>
                        </div>
                        @if (Route::has('login'))
                            <livewire:welcome.navigation />
                        @endif
                    </header>


                </div>
            </div>
        </div>
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
