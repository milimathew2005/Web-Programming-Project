<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Loans & Fines Tracker') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <!-- Fine Summary Card -->
            <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-2xl shadow-lg p-8 mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <span class="text-xs font-semibold text-indigo-300 uppercase tracking-wider">Account Fine Balance</span>
                    <h3 class="text-3xl font-extrabold mt-1">
                        {{ $currencySymbol }}{{ number_format($totalOutstandingFines, 2) }}
                    </h3>
                    <p class="text-xs text-slate-300 mt-1">
                        Calculated overdue charges at {{ $currencySymbol }}{{ number_format(config('library.fine_per_day', 2.00), 2) }} / day.
                    </p>
                </div>
                <div>
                    @if ($totalOutstandingFines > 0)
                        <span class="px-4 py-2 rounded-lg bg-rose-500/20 border border-rose-400/30 text-rose-200 text-xs font-semibold">
                            Outstanding Fine Due
                        </span>
                    @else
                        <span class="px-4 py-2 rounded-lg bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-semibold flex items-center space-x-1">
                            <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Clear Account Record</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Currently Checked Out Loans -->
            <div class="mb-10">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Currently Checked Out Books</h3>

                @if ($activeLoans->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($activeLoans as $loan)
                            @php
                                $isOverdue = $loan->isOverdue();
                                $currentFine = $loan->currentFine();
                            @endphp
                            <div class="bg-white rounded-xl shadow-sm border {{ $isOverdue ? 'border-rose-200 bg-rose-50/10' : 'border-gray-100' }} p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        @if ($isOverdue)
                                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-rose-100 text-rose-800 animate-pulse">
                                                Overdue ({{ $loan->daysOverdue() }} days)
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                Active Loan
                                            </span>
                                        @endif

                                        <span class="text-xs font-mono text-gray-500">
                                            Code: {{ $loan->bookCopy->copy_code }}
                                        </span>
                                    </div>

                                    <h4 class="text-base font-bold text-gray-900 mb-1">
                                        {{ $loan->bookCopy->book->title }}
                                    </h4>
                                    <p class="text-xs text-gray-600 mb-4">
                                        By {{ $loan->bookCopy->book->author }}
                                    </p>

                                    <div class="bg-gray-50 rounded-lg p-3 text-xs space-y-1">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Issued On:</span>
                                            <span class="font-medium text-gray-800">{{ $loan->issued_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Due Date:</span>
                                            <span class="font-bold {{ $isOverdue ? 'text-rose-600' : 'text-gray-800' }}">
                                                {{ $loan->due_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between pt-1 border-t border-gray-200/60">
                                            <span class="text-gray-500">Calculated Fine:</span>
                                            <span class="font-bold {{ $currentFine > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ $currencySymbol }}{{ number_format($currentFine, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-100 p-8 text-center text-sm text-gray-500">
                        You have no books currently checked out.
                    </div>
                @endif
            </div>

            <!-- Loan History -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Returned Book History</h3>

                @if ($pastLoans->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-6">Book Title</th>
                                    <th class="py-3.5 px-6">Copy Code</th>
                                    <th class="py-3.5 px-6">Issued Date</th>
                                    <th class="py-3.5 px-6">Returned Date</th>
                                    <th class="py-3.5 px-6">Fine Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach ($pastLoans as $loan)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="py-4 px-6 font-medium text-gray-900">
                                            {{ $loan->bookCopy->book->title }}
                                        </td>
                                        <td class="py-4 px-6 font-mono text-xs text-gray-600">
                                            {{ $loan->bookCopy->copy_code }}
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-500">
                                            {{ $loan->issued_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-500">
                                            {{ $loan->returned_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($loan->fine_amount > 0)
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $loan->fine_paid ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                    {{ $currencySymbol }}{{ number_format($loan->fine_amount, 2) }}
                                                    ({{ $loan->fine_paid ? 'Paid' : 'Unpaid' }})
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">No Fine</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $pastLoans->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-100 p-6 text-center text-sm text-gray-500">
                        No returned loan history found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
