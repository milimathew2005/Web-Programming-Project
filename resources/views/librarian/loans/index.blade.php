<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Book Loans & Circulation') }}
            </h2>
            <a href="{{ route('librarian.loans.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                + Direct Issue Book
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <!-- Filters & Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <form method="GET" action="{{ route('librarian.loans.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center space-x-1 bg-gray-100 p-1 rounded-lg">
                        @foreach (['active' => 'Active Loans', 'overdue' => 'Overdue Loans', 'all' => 'All Loans', 'returned' => 'Returned History'] as $key => $label)
                            <a href="{{ route('librarian.loans.index', ['status' => $key, 'search' => request('search')]) }}"
                                class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ $status === $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <div class="w-full md:w-72">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Student, book title, or copy code..."
                            class="w-full text-xs py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </form>
            </div>

            <!-- Table -->
            @if ($loans->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="py-3.5 px-6">Student</th>
                                <th class="py-3.5 px-6">Book / Copy</th>
                                <th class="py-3.5 px-6">Issued Date</th>
                                <th class="py-3.5 px-6">Due Date</th>
                                <th class="py-3.5 px-6">Calculated Fine</th>
                                <th class="py-3.5 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach ($loans as $loan)
                                @php
                                    $isOverdue = $loan->isOverdue();
                                    $fine = $loan->currentFine();
                                @endphp
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        {{ $loan->user->name }}
                                        <span class="block text-xs font-mono text-gray-400">{{ $loan->user->student_id }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-gray-900">{{ $loan->bookCopy->book->title }}</div>
                                        <div class="text-xs font-mono text-gray-500">{{ $loan->bookCopy->copy_code }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500">
                                        {{ $loan->issued_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-xs font-semibold {{ $isOverdue ? 'text-rose-600' : 'text-gray-800' }}">
                                        {{ $loan->due_date->format('M d, Y') }}
                                        @if ($isOverdue)
                                            <span class="block text-[10px] text-rose-500 uppercase font-bold">Overdue ({{ $loan->daysOverdue() }} days)</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 font-bold {{ $fine > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                        {{ $currencySymbol }}{{ number_format($fine, 2) }}
                                    </td>
                                    <td class="py-4 px-6 text-right space-x-2">
                                        @if ($loan->returned_at === null)
                                            <form method="POST" action="{{ route('librarian.loans.return', $loan->id) }}" class="inline" onsubmit="return confirm('Process book return for copy {{ $loan->bookCopy->copy_code }}?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded shadow-sm">
                                                    Return Book
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">
                                                Returned on {{ $loan->returned_at->format('M d') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $loans->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-100 p-12 text-center text-gray-500">
                    No loan records match your filters.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
