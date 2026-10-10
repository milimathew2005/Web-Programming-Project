<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('books.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Book Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <!-- Header Info -->
                <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-6 mb-6 gap-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-3 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700">
                                {{ $book->category->name }}
                            </span>
                            <span class="px-3 py-1 rounded-md text-xs font-mono bg-gray-100 text-gray-700">
                                ISBN: {{ $book->isbn }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $book->title }}</h1>
                        <p class="text-sm font-medium text-gray-600">By {{ $book->author }}</p>
                    </div>

                    <!-- Availability Badge -->
                    <div>
                        @if ($availableCopiesCount > 0)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800">
                                <span class="w-2 h-2 me-2 bg-emerald-500 rounded-full"></span>
                                {{ $availableCopiesCount }} of {{ $totalCopiesCount }} Copies Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-800">
                                <span class="w-2 h-2 me-2 bg-rose-500 rounded-full"></span>
                                Currently Unavailable
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Metadata Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 bg-gray-50 rounded-lg p-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase">Publisher</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $book->publisher }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase">Published Year</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $book->published_year }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase">Total Inventory</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $totalCopiesCount }} Copy(ies)</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase">Available Now</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $availableCopiesCount }} Copy(ies)</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Description</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $book->description ?? 'No detailed description available for this book in the library catalog.' }}
                    </p>
                </div>

                <!-- Reservation Action Section -->
                <div class="border-t border-gray-100 pt-6 flex items-center justify-between">
                    <div>
                        @if ($disableReason)
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-xs flex items-center space-x-2">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $disableReason }}</span>
                            </div>
                        @else
                            <p class="text-xs text-gray-500">
                                Reserved copies are held for <strong>48 hours</strong> upon confirmation.
                            </p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('books.reserve', $book->id) }}">
                        @csrf
                        <button type="submit"
                            {{ $disableReason ? 'disabled' : '' }}
                            class="px-6 py-3 rounded-lg text-sm font-semibold transition duration-150 shadow-sm
                            {{ $disableReason ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 text-white' }}">
                            Reserve This Book
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
