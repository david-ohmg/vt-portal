<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="bg-slate-100 text-shadow-black">

<div class="flex flex-col items-center gap-4 mt-4">
    <h1 class="text-2xl font-bold text-center mb-4 mt-4">OHMG VT Portal</h1>
    @if(session('error'))
        <div class="mx-8 my-8  border-l-4 border-red-500 bg-red-100 p-4 text-red-700 opacity-75">{{ session('error') }}</div>
    @endif
    <form action="{{ route('login.post') }}" method="post">
        @csrf
        <label class="block uppercase text-slate-500 mb-2" for="email">Email</label>
        <input class="shadow-sm appearance-none border w-full py-2 px-3 text-slate-500 leading-tight focus:outline-none" type="email" name="email" id="email">
        <label class="block uppercase text-slate-500 mb-2" for="password">Password</label>
        <input class="shadow-sm appearance-none border w-full py-2 px-3 text-slate-500 leading-tight focus:outline-none" type="password" name="password" id="password">
        <button class="px-2 py-1 text-center font-medium text-slate-500 shadow-sm ring-1 ring-slate-100/10 hover:bg-slate-50" type="submit">Login</button>
    </form>
</div>
<footer class="mb-8 mt-12 text-xs text-gray-900 text-center">
    <span class="">&copy; {{ date('Y') }} On Hold Media Group</span>
</footer>
@livewireScripts
</body>
</html>
