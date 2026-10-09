@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Welcome --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ __('messages.welcome_back') }},
            {{ auth()->user()->name }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('messages.user_dashboard_description') }}
        </p>
    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

        {{-- Accounts --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <div
                class="mb-4 flex h-12 w-12 items-center justify-center
                       rounded-xl bg-gray-100 dark:bg-gray-800"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    class="fill-gray-800 dark:fill-white/90"
                >
                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9Z"/>
                </svg>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_accounts') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ number_format($stats['accounts'] ?? 0) }}
            </h3>
        </div>


        {{-- Transactions --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <div
                class="mb-4 flex h-12 w-12 items-center justify-center
                       rounded-xl bg-gray-100 dark:bg-gray-800"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    class="fill-gray-800 dark:fill-white/90"
                >
                    <path d="M7 7h9.6l-1.8-1.8 1.1-1.1L19.6 7.8 15.9 11.5l-1.1-1.1 1.8-1.9H7V7Zm10 10H7.4l1.8 1.8-1.1 1.1-3.7-3.7 3.7-3.7 1.1 1.1-1.8 1.9H17V17Z"/>
                </svg>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_transactions') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ number_format($stats['transactions'] ?? 0) }}
            </h3>
        </div>


        {{-- Goals --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <div
                class="mb-4 flex h-12 w-12 items-center justify-center
                       rounded-xl bg-gray-100 dark:bg-gray-800"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    class="fill-gray-800 dark:fill-white/90"
                >
                    <path d="M12 3a9 9 0 1 0 9 9h-2a7 7 0 1 1-7-7V3Zm1 0v9h9A9 9 0 0 0 13 3Z"/>
                </svg>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.total_goals') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ number_format($stats['goals'] ?? 0) }}
            </h3>
        </div>


        {{-- Income --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.income_this_month') }}
            </p>

            <h3 class="mt-3 text-2xl font-bold text-success-600 dark:text-success-500">
                {{ number_format($stats['income_this_month'] ?? 0, 2) }}
            </h3>
        </div>


        {{-- Expenses --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.expenses_this_month') }}
            </p>

            <h3 class="mt-3 text-2xl font-bold text-error-600 dark:text-error-500">
                {{ number_format($stats['expenses_this_month'] ?? 0, 2) }}
            </h3>
        </div>

    </div>


    {{-- Recent Transactions --}}
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white
               dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <div
            class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4
                   sm:flex-row sm:items-center sm:justify-between
                   dark:border-gray-800"
        >
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ __('messages.recent_transactions') }}
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.your_latest_transactions') }}
                </p>
            </div>

            <a
                href="{{ route('transactions.index') }}"
                class="inline-flex items-center justify-center rounded-lg
                       border border-gray-300 px-4 py-2.5 text-sm font-medium
                       text-gray-700 hover:bg-gray-50
                       dark:border-gray-700 dark:text-gray-300
                       dark:hover:bg-white/[0.03]"
            >
                {{ __('messages.view_all') }}
            </a>
        </div>


        <div class="max-w-full overflow-x-auto custom-scrollbar">

            <table class="min-w-full">

                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">

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

                            <td class="px-5 py-4">
                                <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $transaction->title ?? '-' }}
                                </p>

                                @if($transaction->description)
                                    <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($transaction->description, 40) }}
                                    </span>
                                @endif
                            </td>


                            <td class="px-5 py-4">

                                @if($transaction->type === 'income')

                                    <span
                                        class="rounded-full bg-success-50 px-2.5 py-1
                                               text-theme-xs font-medium text-success-600
                                               dark:bg-success-500/15 dark:text-success-500"
                                    >
                                        {{ __('messages.income') }}
                                    </span>

                                @else

                                    <span
                                        class="rounded-full bg-error-50 px-2.5 py-1
                                               text-theme-xs font-medium text-error-600
                                               dark:bg-error-500/15 dark:text-error-500"
                                    >
                                        {{ __('messages.expense') }}
                                    </span>

                                @endif

                            </td>


                            <td class="px-5 py-4">
                                <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ number_format($transaction->amount, 2) }}
                                </span>
                            </td>


                            <td class="px-5 py-4">
                                <span class="text-theme-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->transaction_date?->format('Y-m-d') }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('messages.no_transactions_found') }}
                                </p>

                                <a
                                    href="{{ route('transactions.create') }}"
                                    class="mt-3 inline-flex rounded-lg bg-brand-500
                                           px-4 py-2 text-sm font-medium text-white
                                           hover:bg-brand-600"
                                >
                                    {{ __('messages.add_first_transaction') }}
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
