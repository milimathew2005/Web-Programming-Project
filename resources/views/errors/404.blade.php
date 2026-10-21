<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | {{ config('app.name', 'Campus Central Library') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 flex flex-col justify-between antialiased">
    <!-- Top Navigation -->
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-3 text-slate-900 group">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md group-hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="font-extrabold text-base tracking-tight">{{ config('app.name', 'Campus Library') }}</span>
            </a>

            <div class="flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                        My Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Error Content -->
    <main class="flex-grow flex items-center justify-center p-6">
        <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-10 text-center">
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 uppercase tracking-wider mb-3">
                HTTP 404 &bull; Not Found
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-3 tracking-tight">
                Page or Book Not Found
            </h1>

            <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                The catalog resource, book record, or page URL you requested cannot be located in the library system. It may have been relocated, removed, or typed incorrectly.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-sm transition">
                    &larr; Back to Library Home
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 border-t border-slate-200 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} {{ config('app.name', 'Campus Central Library') }}. Catalog Reference Service.
    </footer>
</body>
</html>
