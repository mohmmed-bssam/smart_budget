@props([
    'recentTransactions' => [],
])

@php
    $getTypeClasses = function ($type) {
        $baseClasses = 'rounded-full px-2 py-0.5 text-theme-xs font-medium';

        return match ($type) {
            'income' => $baseClasses . ' bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
            'expense' => $baseClasses . ' bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
            default => $baseClasses . ' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
        };
    };
@endphp

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">

    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('messages.recent_transactions') }}
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.recent_transactions_description') }}
            </p>
        </div>

        <a
            href="{{ route('admin.reports.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
        >
            {{ __('messages.view_all') }}
        </a>

    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">

            <thead>
                <tr class="border-t border-gray-100 dark:border-gray-800">

                    <th class="py-3 text-start">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                            {{ __('messages.user') }}
                        </p>
                    </th>

                    <th class="py-3 text-start">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                            {{ __('messages.transaction') }}
                        </p>
                    </th>

                    <th class="py-3 text-start">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                            {{ __('messages.type') }}
                        </p>
                    </th>

                    <th class="py-3 text-start">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                            {{ __('messages.amount') }}
                        </p>
                    </th>

                    <th class="py-3 text-start">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                            {{ __('messages.date') }}
                        </p>
                    </th>

                </tr>
            </thead>

            <tbody>

                @forelse($recentTransactions as $transaction)

                    <tr class="border-t border-gray-100 dark:border-gray-800">

                        {{-- User --}}
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    {{ strtoupper(substr($transaction->user?->name ?? 'U', 0, 1)) }}
                                </div>

                                <div>
                                    <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                        {{ $transaction->user?->name ?? __('messages.unknown_user') }}
                                    </p>

                                    <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                                        {{ $transaction->user?->email }}
                                    </span>
                                </div>

                            </div>
                        </td>

                        {{-- Transaction --}}
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-300">
                                {{ $transaction->title }}
                            </p>
                        </td>

                        {{-- Type --}}
                        <td class="py-3 whitespace-nowrap">
                            <span class="{{ $getTypeClasses($transaction->type) }}">
                                {{ $transaction->type === 'income'
                                    ? __('messages.income')
                                    : __('messages.expense') }}
                            </span>
                        </td>

                        {{-- Amount --}}
                        <td class="py-3 whitespace-nowrap">
                            <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                {{ number_format($transaction->amount, 2) }}
                            </p>
                        </td>

                        {{-- Date --}}
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                {{ optional($transaction->transaction_date)->format('Y-m-d') }}
                            </p>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="py-10 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('messages.no_transactions_found') }}
                            </p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>