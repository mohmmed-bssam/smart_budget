@extends('layouts.app')

@section('content')

@php
    $progressWidth = min($percentage, 100);
@endphp

<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ $goal->title }}
            </h2>

            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.status_' . $goal->status) }}
            </p>

        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('goals.edit', $goal) }}"
                class="rounded-xl border px-4 py-2.5 text-sm font-medium
                       border-[#D8CEC2] text-[#6B4A2D]
                       hover:bg-[#F3E8D8]
                       dark:border-[#594236] dark:text-[#D8B77A]
                       dark:hover:bg-[#30231B]"
            >
                {{ __('messages.edit') }}
            </a>

            <a
                href="{{ route('goals.index') }}"
                class="rounded-xl px-4 py-2.5 text-sm font-medium text-white"
                style="background: linear-gradient(135deg, #5E4029, #8A6238);"
            >
                {{ __('messages.back_to_goals') }}
            </a>

        </div>

    </div>


    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        <div
            class="rounded-2xl border p-5
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div class="flex h-11 w-11 items-center justify-center rounded-xl
                        bg-[#F3E8D8] text-[#6B4A2D]
                        dark:bg-[#30231B] dark:text-[#D8B77A]">

                <i class="fa-solid fa-bullseye"></i>

            </div>

            <p class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.target_amount') }}
            </p>

            <p class="mt-1 text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($goal->target_amount, 2) }}
                {{ $goal->currency }}
            </p>

        </div>


        <div
            class="rounded-2xl border p-5
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div class="flex h-11 w-11 items-center justify-center rounded-xl
                        bg-[#EEF4EF] text-[#4F7A5A]
                        dark:bg-[#233329] dark:text-[#7EAA89]">

                <i class="fa-solid fa-piggy-bank"></i>

            </div>

            <p class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.saved_amount') }}
            </p>

            <p class="mt-1 text-2xl font-bold text-[#4F7A5A] dark:text-[#7EAA89]">
                {{ number_format($savedAmount, 2) }}
                {{ $goal->currency }}
            </p>

        </div>


        <div
            class="rounded-2xl border p-5
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div class="flex h-11 w-11 items-center justify-center rounded-xl
                        bg-[#F8F3EC] text-[#6B4A2D]
                        dark:bg-[#30231B] dark:text-[#D8B77A]">

                <i class="fa-solid fa-coins"></i>

            </div>

            <p class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.remaining') }}
            </p>

            <p class="mt-1 text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($remaining, 2) }}
                {{ $goal->currency }}
            </p>

        </div>

    </div>


    <div
        class="rounded-2xl border p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div class="flex items-center justify-between">

            <div>

                <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ __('messages.goal_progress') }}
                </h3>

                <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.goal_progress_description') }}
                </p>

            </div>

            <span class="text-lg font-bold text-[#6B4A2D] dark:text-[#D8B77A]">
                {{ number_format($percentage, 1) }}%
            </span>

        </div>


        <div class="mt-5 h-4 w-full overflow-hidden rounded-full
                    bg-[#F3E8D8] dark:bg-[#30231B]">

            <div
                class="h-full rounded-full bg-[#B88945]"
                style="width: {{ $progressWidth }}%;"
            ></div>

        </div>


        @if($goal->deadline)

            <div class="mt-5 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">

                <i class="fa-regular fa-calendar me-1"></i>

                {{ __('messages.deadline') }}:

                <strong class="text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ $goal->deadline->format('Y-m-d') }}
                </strong>

            </div>

        @endif

    </div>


    {{-- Savings history --}}
    <div
        class="rounded-2xl border
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div class="border-b p-5 border-[#EEE2D5] dark:border-[#463429]">

            <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ __('messages.savings_history') }}
            </h3>

        </div>


        <div class="divide-y divide-[#EEE2D5] dark:divide-[#463429]">

            @forelse($goal->savings as $saving)

                <div class="flex items-center justify-between gap-4 p-5">

                    <div>

                        <p class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">

                            + {{ number_format($saving->amount, 2) }}
                            {{ $goal->currency }}

                        </p>

                        @if($saving->note)

                            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ $saving->note }}
                            </p>

                        @endif

                    </div>

                    <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                        {{ $saving->date?->format('Y-m-d') }}
                    </p>

                </div>

            @empty

                <div class="p-8 text-center text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.no_savings_found') }}
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection