<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Management') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add Category Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Add New Category</h3>
                    <form method="POST" action="{{ route('librarian.categories.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Category Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Artificial Intelligence"
                                class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                            Create Category
                        </button>
                    </form>
                </div>

                <!-- Categories Table -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Catalog Categories</h3>

                    @if ($categories->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase">
                                        <th class="py-3 px-4">Category Name</th>
                                        <th class="py-3 px-4">Assigned Books</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @foreach ($categories as $cat)
                                        <tr>
                                            <td class="py-3 px-4 font-semibold text-gray-900">
                                                {{ $cat->name }}
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                    {{ $cat->books_count }} Books
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                @if ($cat->books_count === 0)
                                                    <form method="POST" action="{{ route('librarian.categories.destroy', $cat->id) }}" class="inline" onsubmit="return confirm('Delete category {{ $cat->name }}?');">
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
                        <p class="text-xs text-gray-500 py-6 text-center">No categories created yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
