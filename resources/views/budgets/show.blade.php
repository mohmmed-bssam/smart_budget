@extends('layouts.app')

@section('content')

@php
    $isOverBudget = $remaining < 0;
    $progressWidth = min($percentage, 100);
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">

                {{ $budget->category?->name[app()->getLocale()]
                    ?? $budget->category?->name['en']
                    ?? __('messages.budget') }}

            </h2>

            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">

                {{ __('messages.month_' . $budget->month) }}
                {{ $budget->year }}

            </p>

        </div>


        <div class="flex gap-2">

            <a
                href="{{ route('budgets.edit', $budget) }}"
                class="rounded-xl border px-4 py-2.5 text-sm font-medium
                       border-[#D8CEC2] text-[#6B4A2D]
                       hover:bg-[#F3E8D8]
                       dark:border-[#594236] dark:text-[#D8B77A]
                       dark:hover:bg-[#30231B]"
            >
                {{ __('messages.edit') }}
            </a>

            <a
                href="{{ route('budgets.index') }}"
                class="rounded-xl px-4 py-2.5 text-sm font-medium text-white"
                style="background: linear-gradient(135deg, #5E4029, #8A6238);"
            >
                {{ __('messages.back_to_budgets') }}
            </a>

        </div>

    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Budget --}}
        <div
            class="rounded-2xl border p-5
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl
                       bg-[#F3E8D8] text-[#6B4A2D]
                       dark:bg-[#30231B] dark:text-[#D8B77A]"
            >
                <i class="fa-solid fa-wallet"></i>
            </div>

            <p class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.budget_amount') }}
            </p>

            <p class="mt-1 text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">

                {{ number_format($budget->amount, 2) }}

                <span class="text-base font-medium text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ $budget->currency }}
                </span>

            </p>


            @if($budget->currency !== $budget->base_currency)

                <p class="mt-2 text-xs text-[#8A7A68] dark:text-[#C4B3A1]">

                    {{ __('messages.equivalent_to') }}

                    {{ number_format($budget->base_amount, 2) }}

                    {{ $budget->base_currency }}

                </p>

            @endif

        </div>


        {{-- Spent --}}
        <div
            class="rounded-2xl border p-5
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl
                       bg-[#F8EAEA] text-[#B85C5C]
                       dark:bg-[#3A2222] dark:text-[#DF7E7E]"
            >
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>

            <p class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.spent') }}
            </p>

            <p class="mt-1 text-2xl font-bold text-[#B85C5C] dark:text-[#DF7E7E]">

                {{ number_format($spent, 2) }}

                <span class="text-base font-medium">
                    {{ $budget->base_currency }}
                </span>

            </p>

        </div>


        {{-- Remaining --}}
        <div
            class="rounded-2xl border p-5
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl
                    {{ $isOverBudget
                        ? 'bg-[#F8EAEA] text-[#B85C5C] dark:bg-[#3A2222] dark:text-[#DF7E7E]'
                        : 'bg-[#EEF4EF] text-[#4F7A5A] dark:bg-[#233329] dark:text-[#7EAA89]' }}"
            >

                <i class="fa-solid fa-coins"></i>

            </div>

            <p class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.remaining') }}
            </p>

            <p
                class="mt-1 text-2xl font-bold
                    {{ $isOverBudget
                        ? 'text-[#B85C5C] dark:text-[#DF7E7E]'
                        : 'text-[#4F7A5A] dark:text-[#7EAA89]' }}"
            >

                {{ number_format($remaining, 2) }}

                <span class="text-base font-medium">
                    {{ $budget->base_currency }}
                </span>

            </p>

        </div>

    </div>


    {{-- Progress --}}
    <div
        class="rounded-2xl border p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div class="flex items-center justify-between gap-4">

            <div>

                <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ __('messages.budget_progress') }}
                </h3>

                <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.budget_progress_description') }}
                </p>

            </div>


            <span
                class="text-lg font-bold
                    {{ $isOverBudget
                        ? 'text-[#B85C5C] dark:text-[#DF7E7E]'
                        : 'text-[#6B4A2D] dark:text-[#D8B77A]' }}"
            >
                {{ number_format($percentage, 1) }}%
            </span>

        </div>


        <div
            class="mt-5 h-4 w-full overflow-hidden rounded-full
                   bg-[#F3E8D8] dark:bg-[#30231B]"
        >

            <div
                class="h-full rounded-full transition-all duration-300"
                style="
                    width: {{ $progressWidth }}%;
                    background-color:
                        {{ $isOverBudget ? '#B85C5C' : '#B88945' }};
                "
            >
            </div>

        </div>


        @if($isOverBudget)

            <div
                class="mt-5 rounded-xl px-4 py-3
                       bg-[#F8EAEA] text-[#B85C5C]
                       dark:bg-[#3A2222] dark:text-[#DF7E7E]"
            >

                <i class="fa-solid fa-triangle-exclamation me-2"></i>

                {{ __('messages.budget_exceeded_by') }}

                <strong>
                    {{ number_format(abs($remaining), 2) }}
                    {{ $budget->base_currency }}
                </strong>

            </div>

        @endif

    </div>


    {{-- Currency information --}}
    <div
        class="rounded-2xl border p-5
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
            {{ __('messages.currency_details') }}
        </h3>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div>

                <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.budget_currency') }}
                </p>

                <p class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ $budget->currency }}
                </p>

            </div>


            <div>

                <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.base_currency') }}
                </p>

                <p class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ $budget->base_currency }}
                </p>

            </div>


            <div>

                <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.exchange_rate') }}
                </p>

                <p class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ number_format($budget->exchange_rate, 6) }}
                </p>

            </div>

        </div>

    </div>

</div>

@endsection