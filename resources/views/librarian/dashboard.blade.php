<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Librarian Control Panel') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <!-- Stat Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Catalog Books</div>
                    <div class="text-3xl font-extrabold text-gray-900 mt-2">{{ number_format($totalBooks) }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ number_format($totalCopies) }} total physical copies</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Available Copies</div>
                    <div class="text-3xl font-extrabold text-emerald-900 mt-2">{{ number_format($availableCopies) }}</div>
                    <div class="text-xs text-emerald-600 mt-1">Ready for checkout</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Pending Reservations</div>
                    <div class="text-3xl font-extrabold text-amber-900 mt-2">{{ number_format($activeReservations) }}</div>
                    <div class="text-xs text-amber-600 mt-1">Awaiting student collection</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Overdue Loans / Fines</div>
                    <div class="text-3xl font-extrabold text-rose-900 mt-2">{{ number_format($overdueLoansCount) }}</div>
                    <div class="text-xs font-bold text-rose-600 mt-1">
                        {{ $currencySymbol }}{{ number_format($totalOutstandingFines, 2) }} total unpaid
                    </div>
                </div>
            </div>

            <!-- Fine System Configuration & Live Calculator Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- System Fine Config Display -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-900">Active Fine Configuration</h3>
                        <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">System Read-Only</span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500">Default Loan Duration:</span>
                            <span class="font-semibold text-gray-900">{{ config('library.loan_days', 14) }} Days</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500">Daily Overdue Rate:</span>
                            <span class="font-bold text-indigo-600">{{ $currencySymbol }}{{ number_format(config('library.fine_per_day', 2.00), 2) }} / day</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500">Grace Period:</span>
                            <span class="font-semibold text-gray-900">{{ config('library.grace_days', 0) }} Days</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <span class="text-gray-500">Maximum Fine Cap:</span>
                            <span class="font-semibold text-gray-900">{{ $currencySymbol }}{{ number_format(config('library.max_fine', 500.00), 2) }}</span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-gray-500">Reservation Hold Expiry:</span>
                            <span class="font-semibold text-gray-900">{{ config('library.reservation_hold_hours', 48) }} Hours</span>
                        </div>
                    </div>
                </div>

                <!-- Live Fine Calculator Widget -->
                <div class="lg:col-span-2 bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-bold text-white">Live Overdue Fine Calculator</h3>
                            <p class="text-xs text-indigo-200">Simulate fine calculations using the active PHP FineCalculator service.</p>
                        </div>
                        <span class="px-2.5 py-1 rounded bg-indigo-500/30 text-indigo-200 text-xs font-mono">Viva Feature</span>
                    </div>

                    <form method="GET" action="{{ route('librarian.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-xs font-semibold text-indigo-200 uppercase mb-1">Due Date</label>
                            <input type="date" name="due_date" value="{{ $calcDueDate }}" required
                                class="w-full text-xs py-2 rounded border-none bg-white/10 text-white focus:ring-2 focus:ring-indigo-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-indigo-200 uppercase mb-1">Return Date (Optional)</label>
                            <input type="date" name="returned_at" value="{{ $calcReturnDate }}"
                                class="w-full text-xs py-2 rounded border-none bg-white/10 text-white focus:ring-2 focus:ring-indigo-400">
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="w-full py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-bold rounded shadow transition">
                                Calculate Fine
                            </button>
                        </div>
                    </form>

                    @if ($calculatedFineResult !== null)
                        <div class="mt-4 p-4 rounded-lg bg-white/10 border border-indigo-400/30 flex items-center justify-between">
                            <span class="text-xs text-indigo-200 font-medium">Computed Fine Result:</span>
                            <span class="text-xl font-extrabold text-emerald-300">
                                {{ $currencySymbol }}{{ number_format($calculatedFineResult, 2) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Overdue Loans & Expiring Reservations Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Overdue Loans Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-900">Current Overdue Loans</h3>
                        <a href="{{ route('librarian.loans.index', ['status' => 'overdue']) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All</a>
                    </div>

                    @if ($overdueLoansList->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 text-gray-500 uppercase">
                                        <th class="py-2.5 px-2">Student</th>
                                        <th class="py-2.5 px-2">Book Copy</th>
                                        <th class="py-2.5 px-2">Due Date</th>
                                        <th class="py-2.5 px-2 text-right">Fine</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($overdueLoansList as $loan)
                                        <tr>
                                            <td class="py-3 px-2 font-medium text-gray-900">
                                                {{ $loan->user->name }}
                                                <span class="block text-gray-400 font-mono text-[10px]">{{ $loan->user->student_id }}</span>
                                            </td>
                                            <td class="py-3 px-2 font-mono text-gray-600">
                                                {{ $loan->bookCopy->copy_code }}
                                            </td>
                                            <td class="py-3 px-2 text-rose-600 font-semibold">
                                                {{ $loan->due_date->format('M d, Y') }}
                                            </td>
                                            <td class="py-3 px-2 text-right font-bold text-rose-600">
                                                {{ $currencySymbol }}{{ number_format($loan->currentFine(), 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-xs text-gray-500 py-6 text-center">No overdue loans at this moment.</p>
                    @endif
                </div>

                <!-- Expiring Reservations Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-900">Reservations Pending Pickup</h3>
                        <a href="{{ route('librarian.reservations.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View Queue</a>
                    </div>

                    @if ($expiringReservationsList->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 text-gray-500 uppercase">
                                        <th class="py-2.5 px-2">Student</th>
                                        <th class="py-2.5 px-2">Book Title</th>
                                        <th class="py-2.5 px-2">Copy Code</th>
                                        <th class="py-2.5 px-2 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($expiringReservationsList as $reservation)
                                        <tr>
                                            <td class="py-3 px-2 font-medium text-gray-900">
                                                {{ $reservation->user->name }}
                                            </td>
                                            <td class="py-3 px-2 text-gray-700 max-w-[150px] truncate">
                                                {{ $reservation->bookCopy->book->title }}
                                            </td>
                                            <td class="py-3 px-2 font-mono text-gray-600">
                                                {{ $reservation->bookCopy->copy_code }}
                                            </td>
                                            <td class="py-3 px-2 text-right">
                                                <form method="POST" action="{{ route('librarian.reservations.issue', $reservation->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold rounded shadow-sm">
                                                        Issue
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-xs text-gray-500 py-6 text-center">No pending reservations awaiting pickup.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
