<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Reservations Queue') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <!-- Filters & Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <form method="GET" action="{{ route('librarian.reservations.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Status Filter Tabs -->
                    <div class="flex items-center space-x-1 bg-gray-100 p-1 rounded-lg">
                        @foreach (['pending' => 'Pending Queue', 'all' => 'All Reservations', 'collected' => 'Collected', 'cancelled' => 'Cancelled', 'expired' => 'Expired'] as $key => $label)
                            <a href="{{ route('librarian.reservations.index', ['status' => $key, 'search' => request('search')]) }}"
                                class="px-3 py-1.5 rounded-md text-xs font-semibold transition {{ $status === $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Search Input -->
                    <div class="w-full md:w-72">
                        <label for="librarian-reservations-search" class="sr-only">Search Reservations Queue</label>
                        <input type="text" id="librarian-reservations-search" name="search" value="{{ request('search') }}" placeholder="Student name, ID, or copy code..."
                            aria-label="Search reservations by student name, ID, or copy code"
                            class="w-full text-xs py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </form>
            </div>

            <!-- Table -->
            @if ($reservations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-6">Student</th>
                                    <th class="py-3.5 px-6">Book Title</th>
                                    <th class="py-3.5 px-6">Copy Code</th>
                                    <th class="py-3.5 px-6">Reserved At</th>
                                    <th class="py-3.5 px-6">Expires At</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach ($reservations as $res)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="py-4 px-6 font-medium text-gray-900">
                                            {{ $res->user->name }}
                                            <span class="block text-xs font-mono text-gray-400">{{ $res->user->student_id }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-gray-800">
                                            {{ $res->bookCopy->book->title }}
                                        </td>
                                        <td class="py-4 px-6 font-mono text-xs text-gray-600">
                                            {{ $res->bookCopy->copy_code }}
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-500 whitespace-nowrap">
                                            {{ $res->reserved_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="py-4 px-6 text-xs font-semibold whitespace-nowrap {{ $res->expires_at->isPast() ? 'text-rose-600' : 'text-gray-700' }}">
                                            {{ $res->expires_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($res->status === 'pending')
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Pending</span>
                                            @elseif ($res->status === 'collected')
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Collected</span>
                                            @elseif ($res->status === 'cancelled')
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Cancelled</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">Expired</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                            @if ($res->status === 'pending')
                                                <form method="POST" action="{{ route('librarian.reservations.issue', $res->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded shadow-sm">
                                                        Issue Book
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('librarian.reservations.cancel', $res->id) }}" class="inline" onsubmit="return confirm('Cancel reservation for \'{{ addslashes($res->bookCopy->book->title) }}\' and release copy back to available inventory?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 text-xs font-semibold rounded hover:bg-rose-100">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">Processed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    {{ $reservations->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-100 p-12 text-center text-gray-500">
                    No reservations found in this category.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
