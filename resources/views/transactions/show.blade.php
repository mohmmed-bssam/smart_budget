@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ $transaction->title }}
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.transaction_details') }}
            </p>
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('transactions.edit', $transaction) }}"
                class="rounded-lg border border-gray-300 px-4 py-2.5
                       text-sm font-medium text-gray-700
                       dark:border-gray-700 dark:text-gray-300"
            >
                {{ __('messages.edit') }}
            </a>

            <a
                href="{{ route('transactions.index') }}"
                class="rounded-lg bg-brand-500 px-4 py-2.5
                       text-sm font-medium text-white"
            >
                {{ __('messages.back_to_transactions') }}
            </a>

        </div>

    </div>


    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

        {{-- Amount --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.amount') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">

                {{ number_format($transaction->amount, 2) }}

                <span class="text-base font-medium text-gray-500 dark:text-gray-400">
                    {{ $transaction->account?->currency }}
                </span>

            </h3>

        </div>


        {{-- Type --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.type') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold">

                @if($transaction->type === 'income')

                    <span class="text-success-500">
                        {{ __('messages.income') }}
                    </span>

                @else

                    <span class="text-error-500">
                        {{ __('messages.expense') }}
                    </span>

                @endif

            </h3>

        </div>


        {{-- Date --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.date') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ $transaction->transaction_date?->format('Y-m-d') }}
            </h3>

        </div>


        {{-- Account --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.account') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ $transaction->account?->name[app()->getLocale()]
                    ?? $transaction->account?->name['en']
                    ?? '-' }}
            </h3>

        </div>


        {{-- Category --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.category') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ $transaction->category?->name[app()->getLocale()]
                    ?? $transaction->category?->name['en']
                    ?? '-' }}
            </h3>

        </div>

    </div>


    {{-- Description --}}
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5
               dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('messages.description') }}
        </p>

        <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
            {{ $transaction->description ?: __('messages.no_description') }}
        </p>

    </div>

</div>

@endsection
