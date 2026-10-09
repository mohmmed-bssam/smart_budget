@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ $account->name[app()->getLocale()] ?? $account->name['en'] ?? '' }}
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.account_details') }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('accounts.edit', $account) }}"
                class="rounded-lg border border-gray-300 px-4 py-2.5
                       text-sm font-medium text-gray-700 hover:bg-gray-50
                       dark:border-gray-700 dark:text-gray-300
                       dark:hover:bg-white/[0.03]"
            >
                {{ __('messages.edit') }}
            </a>

            <a
                href="{{ route('accounts.index') }}"
                class="rounded-lg bg-brand-500 px-4 py-2.5
                       text-sm font-medium text-white hover:bg-brand-600"
            >
                {{ __('messages.back_to_accounts') }}
            </a>

        </div>

    </div>


    {{-- Account Info --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Name --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.account_name') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ $account->name[app()->getLocale()] ?? $account->name['en'] ?? '' }}
            </h3>
        </div>


        {{-- Type --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.account_type') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">

                @switch($account->type)

                    @case('cash')
                        {{ __('messages.account_type_cash') }}
                        @break

                    @case('bank')
                        {{ __('messages.account_type_bank') }}
                        @break

                    @case('wallet')
                        {{ __('messages.account_type_wallet') }}
                        @break

                    @case('savings')
                        {{ __('messages.account_type_savings') }}
                        @break

                    @default
                        {{ ucfirst($account->type) }}

                @endswitch

            </h3>
        </div>


        {{-- Balance --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.current_balance') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ number_format($account->balance, 2) }}

                <span class="text-base font-medium text-gray-500 dark:text-gray-400">
                    {{ strtoupper($account->currency) }}
                </span>
            </h3>
        </div>


        {{-- Currency --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.currency') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ strtoupper($account->currency) }}
            </h3>
        </div>

    </div>


    {{-- Account Transactions --}}
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
                    {{ __('messages.account_transactions') }}
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.account_transactions_description') }}
                </p>
            </div>

            <a
                href="{{ route('transactions.create') }}"
                class="inline-flex items-center justify-center rounded-lg
                       bg-brand-500 px-4 py-2.5 text-sm font-medium
                       text-white hover:bg-brand-600"
            >
                {{ __('messages.add_transaction') }}
            </a>

        </div>


        {{-- Placeholder for now --}}
        <div class="px-5 py-12 text-center">

            <div
                class="mx-auto flex h-12 w-12 items-center justify-center
                       rounded-full bg-gray-100 dark:bg-gray-800"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    class="fill-gray-500 dark:fill-gray-400"
                >
                    <path d="M7 7h9.6l-1.8-1.8 1.1-1.1L19.6 7.8 15.9 11.5l-1.1-1.1 1.8-1.9H7V7Zm10 10H7.4l1.8 1.8-1.1 1.1-3.7-3.7 3.7-3.7 1.1 1.1-1.8 1.9H17V17Z"/>
                </svg>
            </div>

            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.transactions_will_appear_here') }}
            </p>

        </div>

    </div>

</div>

@endsection
