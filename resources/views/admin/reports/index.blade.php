@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ __('messages.reports') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('messages.reports_description') }}
        </p>
    </div>


    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Users --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_users') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ number_format($reports['users_count'] ?? 0) }}
            </h3>
        </div>


        {{-- Active Users --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.active_users') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-success-600 dark:text-success-500">
                {{ number_format($reports['active_users_count'] ?? 0) }}
            </h3>
        </div>


        {{-- Inactive Users --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.inactive_users') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-error-600 dark:text-error-500">
                {{ number_format($reports['inactive_users_count'] ?? 0) }}
            </h3>
        </div>


        {{-- Accounts --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_accounts') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ number_format($reports['accounts_count'] ?? 0) }}
            </h3>
        </div>


        {{-- Transactions --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_transactions') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ number_format($reports['transactions_count'] ?? 0) }}
            </h3>
        </div>


        {{-- Income Transactions --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.income_transactions') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-success-600 dark:text-success-500">
                {{ number_format($reports['income_transactions_count'] ?? 0) }}
            </h3>
        </div>


        {{-- Expense Transactions --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.expense_transactions') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-error-600 dark:text-error-500">
                {{ number_format($reports['expense_transactions_count'] ?? 0) }}
            </h3>
        </div>


        {{-- Goals --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_goals') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ number_format($reports['goals_count'] ?? 0) }}
            </h3>
        </div>

    </div>


    {{-- Recent Transactions --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white
                dark:border-gray-800 dark:bg-white/[0.03]">

        <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('messages.recent_transactions') }}
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.recent_transactions_description') }}
            </p>
        </div>


        <div class="max-w-full overflow-x-auto custom-scrollbar">

            <table class="min-w-full">

                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.user') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.transaction') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.type') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.amount') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.date') }}
                            </span>
                        </th>

                    </tr>
                </thead>


                <tbody>

                    @forelse($recentTransactions as $transaction)

                        <tr class="border-b border-gray-100 dark:border-gray-800">

                            {{-- User --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 items-center justify-center rounded-full
                                                bg-gray-100 text-sm font-semibold text-gray-700
                                                dark:bg-gray-800 dark:text-gray-300">

                                        {{ strtoupper(substr($transaction->user?->name ?? 'U', 0, 1)) }}

                                    </div>

                                    <div>
                                        <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                            {{ $transaction->user?->name ?? __('messages.unknown_user') }}
                                        </p>

                                        <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                            {{ $transaction->user?->email ?? '-' }}
                                        </span>
                                    </div>

                                </div>

                            </td>


                            {{-- Transaction --}}
                            <td class="px-5 py-4">

                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">
                                    {{ $transaction->title ?? '-' }}
                                </span>

                            </td>


                            {{-- Type --}}
                            <td class="px-5 py-4">

                                @if($transaction->type === 'income')

                                    <span class="rounded-full bg-success-50 px-2.5 py-1
                                                 text-theme-xs font-medium text-success-600
                                                 dark:bg-success-500/15 dark:text-success-500">

                                        {{ __('messages.income') }}

                                    </span>

                                @else

                                    <span class="rounded-full bg-error-50 px-2.5 py-1
                                                 text-theme-xs font-medium text-error-600
                                                 dark:bg-error-500/15 dark:text-error-500">

                                        {{ __('messages.expense') }}

                                    </span>

                                @endif

                            </td>


                            {{-- Amount --}}
                            <td class="px-5 py-4">

                                <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ number_format($transaction->amount, 2) }}
                                </span>

                            </td>


                            {{-- Date --}}
                            <td class="px-5 py-4">

                                <span class="text-theme-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->transaction_date?->format('Y-m-d') }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center">

                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('messages.no_transactions_found') }}
                                </span>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
