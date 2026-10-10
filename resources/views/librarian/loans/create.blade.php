<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('librarian.loans.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Direct Book Issue (Desk Checkout)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Issue Book to Student</h3>
                    <p class="text-xs text-gray-500">Checkout an available copy directly to a registered student without online reservation.</p>
                </div>

                <form method="POST" action="{{ route('librarian.loans.store') }}" class="space-y-6">
                    @csrf

                    <!-- Select Student -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Select Student *</label>
                        <select name="user_id" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->student_id ?? $student->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Select Book Copy -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Select Available Book Copy *</label>
                        <select name="book_copy_id" required class="w-full text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                            <option value="">Select Book Copy</option>
                            @foreach ($availableCopies as $copy)
                                <option value="{{ $copy->id }}" {{ old('book_copy_id') == $copy->id ? 'selected' : '' }}>
                                    [{{ $copy->copy_code }}] {{ $copy->book->title }} (By {{ $copy->book->author }})
                                </option>
                            @endforeach
                        </select>
                        @error('book_copy_id') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-lg text-xs text-indigo-900 space-y-1">
                        <div class="font-semibold">Circulation Policy:</div>
                        <div>Default loan duration is <strong>{{ config('library.loan_days', 14) }} days</strong> from issue date.</div>
                        <div>Max active borrow limit: <strong>3 items</strong> per student.</div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('librarian.loans.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                            Issue Book Copy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
