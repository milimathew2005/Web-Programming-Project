<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reservations') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <!-- Active Pending Reservations Section -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Active Pending Reservations</h3>
                        <p class="text-xs text-gray-500">Collect your reserved books at the library counter before the expiry deadline.</p>
                    </div>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-semibold text-xs rounded-full">
                        {{ $activeReservations->count() }} Active
                    </span>
                </div>

                @if ($activeReservations->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($activeReservations as $reservation)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">
                                            Pending Collection
                                        </span>
                                        <span class="text-xs font-mono text-gray-500">
                                            Code: {{ $reservation->bookCopy->copy_code }}
                                        </span>
                                    </div>

                                    <h4 class="text-base font-bold text-gray-900 mb-1">
                                        {{ $reservation->bookCopy->book->title }}
                                    </h4>
                                    <p class="text-xs text-gray-600 mb-4">
                                        By {{ $reservation->bookCopy->book->author }}
                                    </p>

                                    <div class="bg-gray-50 rounded-lg p-3 text-xs space-y-1 mb-4">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Reserved On:</span>
                                            <span class="font-medium text-gray-800">{{ $reservation->reserved_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Expires At:</span>
                                            <span class="font-bold text-rose-600">{{ $reservation->expires_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end border-t border-gray-100 pt-4">
                                    <form method="POST" action="{{ route('student.reservations.cancel', $reservation->id) }}"
                                        onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg transition">
                                            Cancel Reservation
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-100 p-8 text-center">
                        <p class="text-sm text-gray-500 mb-2">You currently have no active pending reservations.</p>
                        <a href="{{ route('books.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            Browse Book Catalog &rarr;
                        </a>
                    </div>
                @endif
            </div>

            <!-- Past Reservation History Section -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Reservation History</h3>

                @if ($pastReservations->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        <th class="py-3.5 px-6">Book Title</th>
                                        <th class="py-3.5 px-6">Copy Code</th>
                                        <th class="py-3.5 px-6">Reserved At</th>
                                        <th class="py-3.5 px-6">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @foreach ($pastReservations as $reservation)
                                        <tr class="hover:bg-gray-50/50">
                                            <td class="py-4 px-6 font-medium text-gray-900">
                                                {{ $reservation->bookCopy->book->title }}
                                            </td>
                                            <td class="py-4 px-6 font-mono text-xs text-gray-600">
                                                {{ $reservation->bookCopy->copy_code }}
                                            </td>
                                            <td class="py-4 px-6 text-xs text-gray-500">
                                                {{ $reservation->reserved_at->format('M d, Y') }}
                                            </td>
                                            <td class="py-4 px-6">
                                                @if ($reservation->status === 'collected')
                                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                        Collected
                                                    </span>
                                                @elseif ($reservation->status === 'cancelled')
                                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                        Cancelled
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">
                                                        Expired
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4">
                        {{ $pastReservations->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-100 p-6 text-center text-sm text-gray-500">
                        No past reservation records found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
