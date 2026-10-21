<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Campus Central Library') }} - Gateway to Knowledge</title>
    <meta name="description" content="Discover, search, and reserve books from the Campus Central Library catalog. Real-time availability, online holds, and circulation management.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Tailwind Play CDN for landing styling and Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen selection:bg-indigo-500 selection:text-white">
    <!-- Navigation Header -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-700 to-indigo-500 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 group-hover:scale-105 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <span class="font-black text-xl tracking-tight text-slate-900 block leading-tight">Campus Central</span>
                    <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest block">Library Management System</span>
                </div>
            </a>

            <!-- Quick Links -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                <a href="#search-section" class="hover:text-indigo-600 transition">Search Catalog</a>
                <a href="#features" class="hover:text-indigo-600 transition">Library Services</a>
                <a href="#rules" class="hover:text-indigo-600 transition">Circulation Rules</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center space-x-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                            <span>My Dashboard</span>
                            <svg class="w-4 h-4 ms-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2.5 text-xs font-bold text-slate-700 hover:text-indigo-600 transition rounded-lg">
                            Log In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                                <span>Student Register</span>
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- Hero Section with Search Bar -->
        <section id="search-section" class="relative overflow-hidden bg-gradient-to-b from-white via-indigo-50/40 to-slate-50 pt-16 pb-20 lg:pt-24 lg:pb-28 border-b border-slate-200">
            <!-- Background Decorative Blobs -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 pointer-events-none overflow-hidden opacity-30">
                <div class="absolute -top-40 left-1/4 w-96 h-96 bg-indigo-400 rounded-full blur-3xl"></div>
                <div class="absolute -top-20 right-1/4 w-96 h-96 bg-purple-300 rounded-full blur-3xl"></div>
            </div>

            <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Badge -->
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold mb-6 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-ping"></span>
                    <span>Live Campus Catalog & Online Reservation Portal</span>
                </div>

                <!-- Main Hero Heading -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight sm:leading-none mb-6">
                    Empowering Knowledge.<br class="hidden sm:inline" />
                    <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-800 bg-clip-text text-transparent">
                        Discover Your Next Read.
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-600 mb-10 leading-relaxed font-normal">
                    Search thousands of textbooks, academic papers, and reference materials. Check real-time physical copy availability, place holds online, and manage loans with transparent tracking.
                </p>

                <!-- Clean, Prominent Search Bar -->
                <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl shadow-indigo-900/10 border border-slate-200 p-2 sm:p-3 mb-8">
                    <form method="GET" action="{{ route('books.index') }}" class="flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-grow">
                            <label for="catalog-search" class="sr-only">Search library catalog</label>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text"
                                   id="catalog-search"
                                   name="search"
                                   placeholder="Search by title, author, category, or ISBN (e.g. Algorithms, 978-0132350884)..."
                                   class="w-full pl-11 pr-4 py-3 sm:py-3.5 rounded-xl border-none focus:ring-2 focus:ring-indigo-500 text-sm sm:text-base text-slate-900 placeholder:text-slate-400 bg-slate-50/50">
                        </div>

                        <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-3.5 sm:py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/30 transition duration-150 ease-in-out shrink-0">
                            <span>Search Catalog</span>
                            <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Popular Categories / Quick Tags -->
                @if ($categories->count() > 0)
                    <div class="flex flex-wrap items-center justify-center gap-2 text-xs">
                        <span class="text-slate-500 font-semibold me-1">Popular Fields:</span>
                        @foreach ($categories as $cat)
                            <a href="{{ route('books.index', ['category_id' => $cat->id]) }}"
                               class="px-3 py-1 rounded-lg bg-white border border-slate-200 text-slate-700 hover:border-indigo-400 hover:text-indigo-600 font-medium shadow-2xs transition">
                                {{ $cat->name }} <span class="text-slate-400 font-normal">({{ $cat->books_count }})</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <!-- Stats Bar -->
        <section class="bg-white border-b border-slate-200 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-3xl sm:text-4xl font-extrabold text-indigo-600">{{ number_format($totalBooks) }}</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Catalog Titles</div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-3xl sm:text-4xl font-extrabold text-slate-900">{{ number_format($totalCopies) }}</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Total Physical Copies</div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-3xl sm:text-4xl font-extrabold text-emerald-600">{{ number_format($availableCopies) }}</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Copies Available Now</div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-3xl sm:text-4xl font-extrabold text-purple-600">{{ $categories->count() }}</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Subject Categories</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Catalog Books -->
        @if ($recentBooks->count() > 0)
            <section class="py-16 bg-slate-50 border-b border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
                        <div>
                            <span class="text-xs font-extrabold text-indigo-600 uppercase tracking-wider block mb-1">Catalog Preview</span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Recent Additions to the Collection</h2>
                        </div>
                        <a href="{{ route('books.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 inline-flex items-center mt-3 sm:mt-0">
                            <span>View Full Catalog</span>
                            <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($recentBooks as $book)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-indigo-50 text-indigo-700">
                                            {{ $book->category->name }}
                                        </span>
                                        <span class="text-xs font-mono text-slate-400">{{ $book->published_year }}</span>
                                    </div>

                                    <h3 class="font-extrabold text-base text-slate-900 line-clamp-2 mb-1">
                                        {{ $book->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mb-3">By {{ $book->author }}</p>
                                    <p class="text-xs text-slate-600 line-clamp-2 mb-4 leading-relaxed">
                                        {{ $book->description ?? 'Academic library collection reference material.' }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $book->available_copies_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $book->available_copies_count > 0 ? "{$book->available_copies_count} Available" : 'Reserved / Out' }}
                                    </span>
                                    <a href="{{ route('books.show', $book->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                        Details &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Services & Features Grid -->
        <section id="features" class="py-20 bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-xs font-extrabold text-indigo-600 uppercase tracking-widest block mb-2">Designed for Students & Faculty</span>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Complete Modern Library Circulation</h2>
                    <p class="text-sm text-slate-500 mt-2">Everything you need to find resources, reserve copies online, and stay ahead of coursework.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col justify-between hover:border-indigo-200 transition">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Instant Online Reservations</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Lock in available copies directly through the portal with a 48-hour pickup window. Receive real-time hold confirmations.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col justify-between hover:border-indigo-200 transition">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Multi-Copy Inventory Tracking</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Every physical copy carries an individual tracking code. Check exact status across print copies, reserves, and desk loans.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col justify-between hover:border-indigo-200 transition">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Transparent Overdue System</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Transparent fee calculation engine with configurable grace periods, max fine caps, and instant printable payment receipts.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Circulation Rules & Information -->
        <section id="rules" class="py-16 bg-slate-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest block mb-2">Circulation Policies</span>
                        <h2 class="text-3xl font-black tracking-tight text-white mb-6">Fair Lending for All Students</h2>
                        <ul class="space-y-4 text-xs text-slate-300">
                            <li class="flex items-start">
                                <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center me-3 mt-0.5 font-bold">&check;</span>
                                <div><strong class="text-white">Active Limit:</strong> Up to 3 simultaneous active items (loans + active reservations) per student.</div>
                            </li>
                            <li class="flex items-start">
                                <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center me-3 mt-0.5 font-bold">&check;</span>
                                <div><strong class="text-white">Loan Period:</strong> 14 standard days from issue date with renewal options.</div>
                            </li>
                            <li class="flex items-start">
                                <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center me-3 mt-0.5 font-bold">&check;</span>
                                <div><strong class="text-white">Hold Deadline:</strong> Reserved copies are held for 48 hours before returning to circulation.</div>
                            </li>
                            <li class="flex items-start">
                                <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center me-3 mt-0.5 font-bold">&check;</span>
                                <div><strong class="text-white">Overdue Charges:</strong> Configurable daily fine rate capped at a maximum ceiling.</div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-slate-800/80 rounded-2xl p-8 border border-slate-700/60">
                        <h3 class="text-lg font-bold text-white mb-4">Library Desk Hours</h3>
                        <div class="space-y-3 text-xs text-slate-300">
                            <div class="flex justify-between py-2 border-b border-slate-700">
                                <span>Monday – Friday</span>
                                <span class="font-bold text-indigo-300">8:00 AM – 9:00 PM</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-700">
                                <span>Saturday</span>
                                <span class="font-bold text-indigo-300">9:00 AM – 5:00 PM</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-700">
                                <span>Sunday & Holidays</span>
                                <span class="font-bold text-rose-300">Closed (Online Portal 24/7)</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-slate-700">
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition">
                                Access Student Portal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-slate-800">{{ config('app.name', 'Campus Central Library') }}</span>
                <span>&bull; Academic Resource Management</span>
            </div>
            <div>
                &copy; {{ date('Y') }} All rights reserved. Built with Laravel 12.
            </div>
        </div>
    </footer>
</body>
</html>
