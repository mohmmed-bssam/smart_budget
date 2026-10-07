@props([
    'stats' => [],
])
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">

    {{-- Total Users --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24">
                <path d="M8.8 5.6a2.2 2.2 0 1 0 0 4.4 2.2 2.2 0 0 0 0-4.4ZM4.8 15.3C3.7 16.4 3.3 18 3.3 18s.2.7 1 .7h9c.8 0 1-.7 1-.7s-.4-1.6-1.5-2.7c-.9-.9-2.2-1.3-4-1.3s-3.1.4-4 1.3Z"/>
            </svg>
        </div>

        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_users') }}
            </span>

            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{ number_format($stats['users'] ?? 0) }}
            </h4>
        </div>
    </div>


    {{-- Total Accounts --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24">
                <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9Zm2.5-1A1 1 0 0 0 5.5 7.5V8h13v-.5a1 1 0 0 0-1-1h-11Zm-1 3v7a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-7h-13Z"/>
            </svg>
        </div>

        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_accounts') }}
            </span>

            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{ number_format($stats['accounts'] ?? 0) }}
            </h4>
        </div>
    </div>


    {{-- Total Transactions --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24">
                <path d="M7 7h9.6l-1.8-1.8 1.1-1.1L19.6 7.8 15.9 11.5l-1.1-1.1 1.8-1.9H7V7Zm10 10H7.4l1.8 1.8-1.1 1.1-3.7-3.7 3.7-3.7 1.1 1.1-1.8 1.9H17V17Z"/>
            </svg>
        </div>

        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_transactions') }}
            </span>

            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{ number_format($stats['transactions'] ?? 0) }}
            </h4>
        </div>
    </div>


    {{-- Total Goals --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24">
                <path d="M12 3a9 9 0 1 0 9 9h-2a7 7 0 1 1-7-7V3Zm1 0v9h9A9 9 0 0 0 13 3Zm2 2.3A7 7 0 0 1 19.7 10H15V5.3Z"/>
            </svg>
        </div>

        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_goals') }}
            </span>

            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{ number_format($stats['goals'] ?? 0) }}
            </h4>
        </div>
    </div>

</div>
