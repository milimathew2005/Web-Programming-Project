<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Catalog') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form method="GET" action="{{ route('books.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Search Books</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Search by title, author, or ISBN..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Category</label>
                        <select name="category_id" id="category_id" class="w-full py-2.5 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions & Toggle -->
                    <div class="flex items-center justify-between md:justify-end space-x-3">
                        <label class="inline-flex items-center cursor-pointer mr-2">
                            <input type="checkbox" name="available_only" value="1" {{ request('available_only') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ms-2 text-xs font-medium text-gray-700">Available Only</span>
                        </label>

                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-lg shadow-sm transition duration-150 ease-in-out">
                            Filter
                        </button>
                        @if (request()->hasAny(['search', 'category_id', 'available_only']))
                            <a href="{{ route('books.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm rounded-lg transition duration-150 ease-in-out">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Book Grid -->
            @if ($books->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach ($books as $book)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between hover:shadow-md transition duration-200">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-md bg-indigo-50 text-indigo-700">
                                        {{ $book->category->name }}
                                    </span>
                                    <span class="text-xs text-gray-500 font-mono">{{ $book->published_year }}</span>
                                </div>

                                <h3 class="text-base font-bold text-gray-900 line-clamp-2 mb-1 group-hover:text-indigo-600">
                                    {{ $book->title }}
                                </h3>
                                <p class="text-xs text-gray-600 mb-3">By {{ $book->author }}</p>

                                <p class="text-xs text-gray-500 line-clamp-3 mb-4">
                                    {{ $book->description ?? 'No description available for this catalog item.' }}
                                </p>
                            </div>

                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                <!-- Availability Badge -->
                                <div>
                                    @if ($book->available_copies_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 me-1.5 bg-emerald-500 rounded-full"></span>
                                            {{ $book->available_copies_count }} of {{ $book->total_copies_count }} Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                            <span class="w-1.5 h-1.5 me-1.5 bg-rose-500 rounded-full"></span>
                                            Unavailable
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('books.show', $book->id) }}"
                                    class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-900">
                                    Details
                                    <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $books->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center my-6">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">No Books Found</h3>
                    <p class="text-sm text-gray-500 mb-4">We couldn't find any books matching your search filters.</p>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-medium rounded-md shadow-sm hover:bg-indigo-700">
                        Clear All Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
