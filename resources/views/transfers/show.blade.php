@extends('layouts.app')
@section('title', __('messages.transfer_details'))
@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ __('messages.transfer_details') }}
            </h2>

            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ $transfer->transfer_date?->format('Y-m-d') }}
            </p>

        </div>

        <a
            href="{{ route('transfers.index') }}"
            class="rounded-xl px-4 py-2.5
                   text-sm font-medium text-white"
            style="background: linear-gradient(135deg, #5E4029, #8A6238);"
        >
            {{ __('messages.back_to_transfers') }}
        </a>

    </div>


    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

        {{-- From --}}
        <div
            class="rounded-2xl border p-6
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div class="flex h-11 w-11 items-center justify-center rounded-xl
                        bg-[#F8EAEA] text-[#B85C5C]
                        dark:bg-[#3A2222] dark:text-[#DF7E7E]">

                <i class="fa-solid fa-arrow-up"></i>

            </div>

            <p class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.from_account') }}
            </p>

            <h3 class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">

                {{ $transfer->fromAccount?->name[app()->getLocale()]
                    ?? $transfer->fromAccount?->name['en']
                    ?? '-' }}

            </h3>

            <p class="mt-4 text-2xl font-bold text-[#B85C5C] dark:text-[#DF7E7E]">

                - {{ number_format($transfer->from_amount, 2) }}
                {{ $transfer->from_currency }}

            </p>

        </div>


        {{-- To --}}
        <div
            class="rounded-2xl border p-6
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div class="flex h-11 w-11 items-center justify-center rounded-xl
                        bg-[#EEF4EF] text-[#4F7A5A]
                        dark:bg-[#233329] dark:text-[#7EAA89]">

                <i class="fa-solid fa-arrow-down"></i>

            </div>

            <p class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.to_account') }}
            </p>

            <h3 class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">

                {{ $transfer->toAccount?->name[app()->getLocale()]
                    ?? $transfer->toAccount?->name['en']
                    ?? '-' }}

            </h3>

            <p class="mt-4 text-2xl font-bold text-[#4F7A5A] dark:text-[#7EAA89]">

                + {{ number_format($transfer->to_amount, 2) }}
                {{ $transfer->to_currency }}

            </p>

        </div>

    </div>


    <div
        class="rounded-2xl border p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
            {{ __('messages.transfer_information') }}
        </h3>

        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

            <div>

                <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.exchange_rate') }}
                </p>

                <p class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ number_format($transfer->exchange_rate, 8) }}
                </p>

            </div>


            <div>

                <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.transfer_date') }}
                </p>

                <p class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ $transfer->transfer_date?->format('Y-m-d') }}
                </p>

            </div>


            <div>

                <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.note') }}
                </p>

                <p class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ $transfer->note ?: '-' }}
                </p>

            </div>

        </div>

    </div>


    <div class="flex justify-end">

        <form
            method="POST"
            action="{{ route('transfers.destroy', $transfer) }}"
            onsubmit="return confirm(@js(__('messages.reverse_transfer_confirmation')))"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="rounded-xl border
                       border-[#E6BDBD]
                       px-5 py-2.5
                       text-sm font-medium
                       text-[#B85C5C]
                       hover:bg-[#F8EAEA]

                       dark:border-[#674040]
                       dark:text-[#DF7E7E]
                       dark:hover:bg-[#3A2222]"
            >
                {{ __('messages.reverse_transfer') }}
            </button>

        </form>

    </div>

</div>

@endsection
