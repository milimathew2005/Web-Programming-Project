@if (session('success'))
    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
@endif

@if (session('info'))
    <div class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-sm flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('info') }}</span>
        </div>
    </div>
@endif
