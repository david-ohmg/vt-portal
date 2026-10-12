<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('code') &middot; @yield('title') &middot; {{ config('app.name') }}</title>

    <script>
        (function() {
            try {
                const saved = localStorage.getItem('theme');
                const theme = (saved === 'light' || saved === 'dark')
                    ? saved
                    : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
                document.documentElement.dataset.theme = theme;
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>
<body class="font-sans flex flex-col">

<main class="flex flex-1 items-center justify-center px-4 py-16">
    <div class="w-full max-w-sm text-center">
        <div class="mx-auto mb-4 flex size-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-violet-500 text-white shadow-lg shadow-brand-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
            </svg>
        </div>

        <p class="text-sm font-semibold text-zinc-500 dark:text-zinc-400">@yield('code')</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight">
            <span class="brand-link">@yield('title')</span>
        </h1>
        <p class="mt-2 mb-8 text-sm text-zinc-500 dark:text-zinc-400">@yield('message')</p>

        <a href="{{ url('/') }}" class="btn-primary">Back to the portal</a>
    </div>
</main>

<footer class="site-footer mt-0">
    <span>&copy; {{ date('Y') }} On Hold Media Group</span>
</footer>
</body>
</html>
