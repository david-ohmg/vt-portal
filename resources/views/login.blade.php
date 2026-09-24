<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const theme = (saved === 'light' || saved === 'dark')
                ? saved
                : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
            document.documentElement.dataset.theme = theme;
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-sans flex flex-col">

<div class="absolute top-4 right-4">
    <button
        type="button"
        id="theme-toggle"
        class="theme-toggle"
        aria-label="Toggle dark mode"
        title="Toggle theme">
        <svg id="theme-icon-moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
        </svg>
        <svg id="theme-icon-sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 hidden">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
        </svg>
    </button>
</div>

<main class="flex flex-1 items-center justify-center px-4 py-16">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex size-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-violet-500 text-white shadow-lg shadow-brand-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">
                <span class="brand-link">OHMG VT Portal</span>
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Sign in to your voice talent account</p>
        </div>

        @if(session('success'))
            <div role="alert" class="alert alert-success mx-0 sm:mx-0 mt-0">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div role="alert" class="alert alert-error mx-0 sm:mx-0 mt-0">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="post" class="card space-y-5">
            @csrf
            <div>
                <label class="form-label" for="email">Email</label>
                <input
                    class="form-input"
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    autocomplete="email"
                    required
                    autofocus
                    @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p id="email-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label" for="password">Password</label>
                <input
                    class="form-input"
                    type="password"
                    name="password"
                    id="password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                    @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                @error('password')
                    <p id="password-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <button class="btn-primary" type="submit">
                Sign in
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </form>
    </div>
</main>

<footer class="site-footer mt-0">
    <span>&copy; {{ date('Y') }} On Hold Media Group</span>
</footer>
@livewireScripts
</body>
</html>
