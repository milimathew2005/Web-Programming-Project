<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fine Receipt - Loan #{{ $loan->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .receipt-card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-6 font-sans">
    <div class="max-w-2xl mx-auto">
        <!-- Print / Back Controls -->
        <div class="mb-6 flex justify-between items-center no-print">
            <button onclick="window.history.back()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-semibold rounded">
                &larr; Back
            </button>
            <button onclick="window.print()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow">
                🖨️ Print Receipt
            </button>
        </div>

        <!-- Receipt Document Card -->
        <div class="receipt-card bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <!-- Header -->
            <div class="border-b-2 border-gray-900 pb-6 mb-6 flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">CAMPUS CENTRAL LIBRARY</h1>
                    <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mt-1">Official Library Fine Receipt</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs font-mono text-gray-400">RECEIPT NO:</span>
                    <span class="text-sm font-bold font-mono text-gray-900">REC-{{ sprintf('%06d', $loan->id) }}</span>
                    <span class="block text-xs text-gray-500 mt-1">{{ now()->format('M d, Y h:i A') }}</span>
                </div>
            </div>

            <!-- Student & Book Details -->
            <div class="grid grid-cols-2 gap-6 mb-6 bg-gray-50 rounded-lg p-4 text-xs">
                <div>
                    <span class="text-gray-500 block font-semibold uppercase">Student Information</span>
                    <p class="font-bold text-gray-900 text-sm mt-1">{{ $loan->user->name }}</p>
                    <p class="text-gray-600 font-mono">ID: {{ $loan->user->student_id ?? 'N/A' }}</p>
                    <p class="text-gray-600">{{ $loan->user->email }}</p>
                </div>
                <div>
                    <span class="text-gray-500 block font-semibold uppercase">Book Details</span>
                    <p class="font-bold text-gray-900 text-sm mt-1">{{ $loan->bookCopy->book->title }}</p>
                    <p class="text-gray-600 font-mono">Copy Code: {{ $loan->bookCopy->copy_code }}</p>
                    <p class="text-gray-600">Author: {{ $loan->bookCopy->book->author }}</p>
                </div>
            </div>

            <!-- Loan Dates Summary -->
            <table class="w-full text-left text-xs mb-6 border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 font-bold border-b border-gray-200 uppercase">
                        <th class="py-2.5 px-3">Issued Date</th>
                        <th class="py-2.5 px-3">Due Date</th>
                        <th class="py-2.5 px-3">Returned Date</th>
                        <th class="py-2.5 px-3 text-right">Overdue Days</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-3">{{ $loan->issued_at->format('M d, Y') }}</td>
                        <td class="py-3 px-3 font-semibold text-rose-600">{{ $loan->due_date->format('M d, Y') }}</td>
                        <td class="py-3 px-3">{{ $loan->returned_at ? $loan->returned_at->format('M d, Y') : 'Not Returned (Active)' }}</td>
                        <td class="py-3 px-3 text-right font-bold text-rose-600">{{ $overdueDays }} Days</td>
                    </tr>
                </tbody>
            </table>

            <!-- Fine Breakdown -->
            <div class="border-t border-gray-200 pt-4 mb-8">
                <div class="flex justify-between text-xs py-1 text-gray-600">
                    <span>Daily Overdue Fine Rate:</span>
                    <span>{{ $currencySymbol }}{{ number_format(config('library.fine_per_day', 2.00), 2) }} / day</span>
                </div>
                <div class="flex justify-between text-xs py-1 text-gray-600">
                    <span>Grace Days Allowed:</span>
                    <span>{{ config('library.grace_days', 0) }} Days</span>
                </div>
                <div class="flex justify-between text-xs py-1 text-gray-600">
                    <span>Chargeable Overdue Days:</span>
                    <span>{{ $overdueDays }} Days</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-gray-900 border-t border-gray-200 pt-3 mt-2">
                    <span>Total Fine Charged:</span>
                    <span class="text-base text-rose-700">{{ $currencySymbol }}{{ number_format($calculatedFine > 0 ? $calculatedFine : $loan->fine_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs font-semibold py-1 mt-1">
                    <span>Payment Status:</span>
                    <span class="{{ $loan->fine_paid ? 'text-emerald-600 font-bold uppercase' : 'text-rose-600 font-bold uppercase' }}">
                        {{ $loan->fine_paid ? 'PAID IN FULL' : 'UNPAID' }}
                    </span>
                </div>
            </div>

            <!-- Signatures -->
            <div class="pt-8 border-t border-dashed border-gray-300 grid grid-cols-2 gap-8 text-center text-xs">
                <div>
                    <div class="h-10"></div>
                    <div class="border-t border-gray-400 pt-1 font-semibold text-gray-700">Student Signature</div>
                </div>
                <div>
                    <div class="h-10"></div>
                    <div class="border-t border-gray-400 pt-1 font-semibold text-gray-700">Authorized Librarian Signature</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
