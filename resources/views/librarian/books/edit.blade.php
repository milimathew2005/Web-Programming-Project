<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('librarian.books.show', $book->id) }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Book: ') . $book->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <form method="POST" action="{{ route('librarian.books.update', $book->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Book Title *</label>
                            <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('title') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Author -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Author *</label>
                            <input type="text" name="author" value="{{ old('author', $book->author) }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('author') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ISBN *</label>
                            <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                            @error('isbn') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Category *</label>
                            <select name="category_id" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Publisher -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Publisher *</label>
                            <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('publisher') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Published Year -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Published Year *</label>
                            <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('published_year') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cover Image Upload -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Update Cover Image (Optional)</label>
                            <input type="file" name="cover_image" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('cover_image') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Description</label>
                            <textarea name="description" rows="4" class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $book->description) }}</textarea>
                            @error('description') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('librarian.books.show', $book->id) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
