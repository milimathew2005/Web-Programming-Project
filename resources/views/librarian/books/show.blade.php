<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('librarian.books.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Book Detail & Physical Copy Manager') }}
                </h2>
            </div>
            <a href="{{ route('librarian.books.edit', $book->id) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg">
                Edit Book Metadata
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Book Metadata Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    @if ($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                    @endif
                    <span class="px-2.5 py-1 text-xs font-semibold rounded bg-indigo-50 text-indigo-700">
                        {{ $book->category->name }}
                    </span>
                    <h3 class="text-xl font-bold text-gray-900 mt-2 mb-1">{{ $book->title }}</h3>
                    <p class="text-xs font-medium text-gray-600 mb-4">By {{ $book->author }}</p>

                    <div class="space-y-2 text-xs border-t border-gray-100 pt-3">
                        <div class="flex justify-between"><span class="text-gray-500">ISBN:</span> <span class="font-mono font-bold">{{ $book->isbn }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Publisher:</span> <span class="font-medium">{{ $book->publisher }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Published Year:</span> <span class="font-medium">{{ $book->published_year }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Available / Total:</span> <span class="font-bold text-emerald-600">{{ $book->availableCopiesCount() }} / {{ $book->totalCopiesCount() }}</span></div>
                    </div>

                    <p class="text-xs text-gray-500 mt-4 border-t border-gray-100 pt-3">
                        {{ $book->description ?? 'No description provided.' }}
                    </p>
                </div>

                <!-- Copies Manager Card -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Physical Copies Inventory</h3>
                            <p class="text-xs text-gray-500">Manage barcodes, copy tracking codes, and status.</p>
                        </div>

                        <form method="POST" action="{{ route('librarian.books.add-copy', $book->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm">
                                + Add Copy
                            </button>
                        </form>
                    </div>

                    @if ($book->copies->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase">
                                        <th class="py-3 px-4">Copy Code</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4">Added On</th>
                                        <th class="py-3 px-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @foreach ($book->copies as $copy)
                                        <tr>
                                            <td class="py-3 px-4 font-mono font-bold text-gray-900">
                                                {{ $copy->copy_code }}
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($copy->status === 'available')
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Available</span>
                                                @elseif ($copy->status === 'reserved')
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Reserved</span>
                                                @elseif ($copy->status === 'borrowed')
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">Borrowed</span>
                                                @else
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Lost</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-xs text-gray-500">
                                                {{ $copy->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                @if ($copy->status === 'available')
                                                    <form method="POST" action="{{ route('librarian.copies.destroy', $copy->id) }}" class="inline" onsubmit="return confirm('Remove copy {{ $copy->copy_code }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold">Delete</button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-gray-400">In Use</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center text-xs text-gray-500">
                            No physical copies generated yet for this book.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
