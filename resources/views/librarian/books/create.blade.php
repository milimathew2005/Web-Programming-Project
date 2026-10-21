<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('librarian.books.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Book to Inventory') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <form method="POST" action="{{ route('librarian.books.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="book_title" class="block text-xs font-bold text-gray-700 uppercase mb-1">Book Title *</label>
                            <input type="text" id="book_title" name="title" value="{{ old('title') }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('title') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Author -->
                        <div>
                            <label for="book_author" class="block text-xs font-bold text-gray-700 uppercase mb-1">Author *</label>
                            <input type="text" id="book_author" name="author" value="{{ old('author') }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('author') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label for="book_isbn" class="block text-xs font-bold text-gray-700 uppercase mb-1">ISBN (Unique) *</label>
                            <input type="text" id="book_isbn" name="isbn" value="{{ old('isbn') }}" required placeholder="e.g. 978-0132350884"
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                            @error('isbn') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="book_category_id" class="block text-xs font-bold text-gray-700 uppercase mb-1">Category *</label>
                            <select id="book_category_id" name="category_id" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Publisher -->
                        <div>
                            <label for="book_publisher" class="block text-xs font-bold text-gray-700 uppercase mb-1">Publisher *</label>
                            <input type="text" id="book_publisher" name="publisher" value="{{ old('publisher') }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('publisher') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Published Year -->
                        <div>
                            <label for="book_published_year" class="block text-xs font-bold text-gray-700 uppercase mb-1">Published Year *</label>
                            <input type="number" id="book_published_year" name="published_year" value="{{ old('published_year', date('Y')) }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('published_year') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Initial Copies Count -->
                        <div>
                            <label for="book_initial_copies" class="block text-xs font-bold text-gray-700 uppercase mb-1">Initial Physical Copies *</label>
                            <input type="number" id="book_initial_copies" name="initial_copies" value="{{ old('initial_copies', 2) }}" min="1" max="20" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <span class="text-[11px] text-gray-500">Auto-generates unique copy tracking codes (e.g. BK0015-C1)</span>
                            @error('initial_copies') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cover Image Upload -->
                        <div class="md:col-span-2">
                            <label for="book_cover_image" class="block text-xs font-bold text-gray-700 uppercase mb-1">Cover Image (Optional)</label>
                            <input type="file" id="book_cover_image" name="cover_image" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('cover_image') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="book_description" class="block text-xs font-bold text-gray-700 uppercase mb-1">Description</label>
                            <textarea id="book_description" name="description" rows="4" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                            @error('description') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('librarian.books.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                            Create Book & Generate Copies
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
