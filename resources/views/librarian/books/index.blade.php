<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Book Inventory & Copy Management') }}
            </h2>
            <a href="{{ route('librarian.books.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                + Add New Book
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <form method="GET" action="{{ route('librarian.books.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="librarian-search" class="block text-xs font-semibold text-gray-700 uppercase mb-2">Search Catalog</label>
                        <input type="text" id="librarian-search" name="search" value="{{ request('search') }}" placeholder="Title, author, or ISBN..."
                            class="w-full text-sm py-2.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="librarian-category" class="block text-xs font-semibold text-gray-700 uppercase mb-2">Category</label>
                        <select id="librarian-category" name="category_id" class="w-full text-sm py-2.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
                            Filter
                        </button>
                        @if (request()->hasAny(['search', 'category_id']))
                            <a href="{{ route('librarian.books.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Books Table -->
            @if ($books->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-6">Book Info</th>
                                    <th class="py-3.5 px-6">Category</th>
                                    <th class="py-3.5 px-6">ISBN</th>
                                    <th class="py-3.5 px-6">Copies (Avail / Total)</th>
                                    <th class="py-3.5 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach ($books as $book)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-gray-900">{{ $book->title }}</div>
                                            <div class="text-xs text-gray-500">By {{ $book->author }} ({{ $book->published_year }})</div>
                                        </td>
                                        <td class="py-4 px-6 text-xs font-medium text-gray-700">
                                            {{ $book->category->name }}
                                        </td>
                                        <td class="py-4 px-6 font-mono text-xs text-gray-600">
                                            {{ $book->isbn }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $book->available_copies_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                {{ $book->available_copies_count }} / {{ $book->total_copies_count }} Available
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('librarian.books.show', $book->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded hover:bg-indigo-100 transition">
                                                Copies / View
                                            </a>
                                            <a href="{{ route('librarian.books.edit', $book->id) }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded hover:bg-gray-200 transition">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('librarian.books.destroy', $book->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($book->title) }}\'? This will permanently delete its catalog and inventory records.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 text-xs font-semibold rounded hover:bg-rose-100 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $books->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-100 p-12 text-center text-gray-500">
                    No books match your criteria.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
