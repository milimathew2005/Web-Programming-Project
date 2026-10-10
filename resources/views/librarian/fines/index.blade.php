<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Overdue Fine Tracker & Fee Management') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <!-- Active Fine Rates Config Summary Bar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-3">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Active System Fine Rates (Config Read-Only)</h3>
                    <span class="text-[11px] font-mono text-gray-500">config/library.php</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="text-gray-500 block">Daily Rate:</span>
                        <span class="font-bold text-indigo-600">{{ $fineConfig['currency_symbol'] }}{{ number_format($fineConfig['fine_per_day'], 2) }} / day</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Grace Period:</span>
                        <span class="font-semibold text-gray-900">{{ $fineConfig['grace_days'] }} Days</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Max Fine Cap:</span>
                        <span class="font-semibold text-gray-900">{{ $fineConfig['currency_symbol'] }}{{ number_format($fineConfig['max_fine'], 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Max Active Borrow Limit:</span>
                        <span class="font-semibold text-gray-900">{{ $fineConfig['max_active_items'] }} Items</span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <form method="GET" action="{{ route('librarian.fines.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center space-x-1 bg-gray-100 p-1 rounded-lg">
                        @foreach (['unpaid' => 'Unpaid Fines', 'paid' => 'Paid Fine Records', 'all' => 'All Fine Records'] as $key => $label)
                            <a href="{{ route('librarian.fines.index', ['status' => $key, 'search' => request('search')]) }}"
                                class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ $status === $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <div class="w-full md:w-72">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Student name, ID, or copy code..."
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
                                <th class="py-3.5 px-6">Due Date</th>
                                <th class="py-3.5 px-6">Overdue Days</th>
                                <th class="py-3.5 px-6">Fine Amount</th>
                                <th class="py-3.5 px-6">Payment Status</th>
                                <th class="py-3.5 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach ($loans as $loan)
                                @php
                                    $fine = $loan->currentFine();
                                    $overdueDays = $loan->daysOverdue();
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
                                    <td class="py-4 px-6 text-xs text-gray-600">
                                        {{ $loan->due_date->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-xs font-bold text-rose-600">
                                        {{ $overdueDays }} Days
                                    </td>
                                    <td class="py-4 px-6 font-extrabold text-rose-700">
                                        {{ $fineConfig['currency_symbol'] }}{{ number_format($fine > 0 ? $fine : $loan->fine_amount, 2) }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($loan->fine_paid)
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Paid</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right space-x-2">
                                        @if (! $loan->fine_paid)
                                            <form method="POST" action="{{ route('librarian.fines.pay', $loan->id) }}" class="inline" onsubmit="return confirm('Mark fine as paid?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded shadow-sm">
                                                    Mark as Paid
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('librarian.fines.receipt', $loan->id) }}" target="_blank" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded inline-flex items-center space-x-1">
                                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            <span>Receipt</span>
                                        </a>
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
                    No fine records found.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
