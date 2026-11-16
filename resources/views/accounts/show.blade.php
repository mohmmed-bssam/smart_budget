@extends('layouts.app')
@section('title', __('messages.account_details'))
@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ $account->name[app()->getLocale()] ?? ($account->name['en'] ?? '') }}
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.account_details') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">

                <a href="{{ route('accounts.edit', $account) }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5
                       text-sm font-medium text-gray-700 hover:bg-gray-50
                       dark:border-gray-700 dark:text-gray-300
                       dark:hover:bg-white/[0.03]">
                    {{ __('messages.edit') }}
                </a>

                <a href="{{ route('accounts.index') }}"
                    class="rounded-lg bg-brand-500 px-4 py-2.5
                       text-sm font-medium text-white hover:bg-brand-600">
                    {{ __('messages.back_to_accounts') }}
                </a>

            </div>

        </div>


        {{-- Account Info --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Name --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.account_name') }}
                </p>

                <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ $account->name[app()->getLocale()] ?? ($account->name['en'] ?? '') }}
                </h3>
            </div>


            {{-- Type --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]">
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
                   dark:border-gray-800 dark:bg-white/[0.03]">
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
                   dark:border-gray-800 dark:bg-white/[0.03]">
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
               dark:border-gray-800 dark:bg-white/[0.03]">

            <div
                class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4
                   sm:flex-row sm:items-center sm:justify-between
                   dark:border-gray-800">

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ __('messages.account_transactions') }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('messages.account_transactions_description') }}
                    </p>
                </div>

 <div class="flex flex-wrap items-center gap-2">

    <a
        href="{{ route('transactions.create') }}"
        class="inline-flex items-center justify-center rounded-xl
               px-4 py-2.5 text-sm font-medium text-white"
        style="background: linear-gradient(135deg, #5E4029, #8A6238);"
    >
        <i class="fa-solid fa-plus me-2"></i>

        {{ __('messages.add_transaction') }}
    </a>


    <button
        type="button"

        onclick="
            const modal = document.getElementById('statementModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        "

        class="inline-flex items-center justify-center rounded-xl
               border border-[#D8CEC2]
               px-4 py-2.5
               text-sm font-medium
               text-[#6B4A2D]
               hover:bg-[#F3E8D8]

               dark:border-[#594236]
               dark:text-[#D8B77A]
               dark:hover:bg-[#30231B]"
    >

        <i class="fa-solid fa-file-lines me-2"></i>

        {{ __('messages.account_statement') }}

    </button>

</div>
            </div>


            <div class="divide-y divide-[#EEE2D5] dark:divide-[#463429]">

                @forelse($movements as $movement)
                    @php
                        $isIncoming = $movement['direction'] === 'in';

                        $isTransfer = in_array($movement['movement_type'], ['transfer_in', 'transfer_out']);

                        $isSaving = $movement['movement_type'] === 'saving';
                    @endphp


                    <div
                        class="flex flex-col gap-4 px-5 py-4
                   sm:flex-row
                   sm:items-center
                   sm:justify-between">

                        {{-- Movement Info --}}
                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-11 w-11 shrink-0
                           items-center justify-center
                           rounded-xl

                           {{ $isIncoming
                               ? 'bg-[#EEF4EF] text-[#4F7A5A] dark:bg-[#233329] dark:text-[#7EAA89]'
                               : 'bg-[#F8EAEA] text-[#B85C5C] dark:bg-[#3A2222] dark:text-[#DF7E7E]' }}">

                                @if ($isTransfer)
                                    <i class="fa-solid fa-right-left"></i>
                                @elseif($isSaving)
                                    <i class="fa-solid fa-piggy-bank"></i>
                                @else
                                    <i
                                        class="fa-solid
                                   {{ $isIncoming ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                @endif

                            </div>


                            <div class="min-w-0">

                                {{-- Title --}}
                                <p
                                    class="font-semibold
                               text-[#2B2118]
                               dark:text-[#F7F1E8]">

                                    @if ($movement['movement_type'] === 'transfer_in')
                                        {{ __('messages.transfer_in_from') }}

                                        <span class="font-bold">
                                            {{ $movement['from_account_name'] }}
                                        </span>
                                    @elseif($movement['movement_type'] === 'transfer_out')
                                        {{ __('messages.transfer_out_to') }}

                                        <span class="font-bold">
                                            {{ $movement['to_account_name'] }}
                                        </span>
                                    @else
                                        {{ $movement['title'] }}
                                    @endif

                                </p>


                                {{-- Normal subtitle/date --}}
                                <div
                                    class="mt-1 flex flex-wrap
                               items-center gap-2
                               text-xs
                               text-[#8A7A68]
                               dark:text-[#C4B3A1]">

                                    @if (!$isTransfer && $movement['subtitle'])
                                        <span>
                                            {{ $movement['subtitle'] }}
                                        </span>

                                        <span>•</span>
                                    @endif


                                    <span>
                                        {{ $movement['date'] ? \Carbon\Carbon::parse($movement['date'])->format('Y-m-d') : '' }}
                                    </span>

                                </div>



                                {{-- Transfer Details --}}
                                @if ($isTransfer)
                                    <div
                                        class="mt-3 rounded-xl
                                   bg-[#F8F3EC]
                                   px-4 py-3
                                   text-xs
                                   text-[#8A7A68]

                                   dark:bg-[#30231B]
                                   dark:text-[#C4B3A1]">

                                        <div
                                            class="grid grid-cols-1 gap-2
                                       sm:grid-cols-2">

                                            {{-- From --}}
                                            <div>

                                                <span class="font-medium">
                                                    {{ __('messages.from') }}:
                                                </span>

                                                <strong
                                                    class="text-[#2B2118]
                                               dark:text-[#F7F1E8]">
                                                    {{ $movement['from_account_name'] }}
                                                </strong>

                                                <span>
                                                    ({{ $movement['from_currency'] }})
                                                </span>

                                            </div>


                                            {{-- To --}}
                                            <div>

                                                <span class="font-medium">
                                                    {{ __('messages.to') }}:
                                                </span>

                                                <strong
                                                    class="text-[#2B2118]
                                               dark:text-[#F7F1E8]">
                                                    {{ $movement['to_account_name'] }}
                                                </strong>

                                                <span>
                                                    ({{ $movement['to_currency'] }})
                                                </span>

                                            </div>

                                        </div>


                                        {{-- Different currencies --}}
                                        @if ($movement['from_currency'] !== $movement['to_currency'])
                                            <div
                                                class="mt-3 border-t
                                           border-[#E8DDCF]
                                           pt-3

                                           dark:border-[#463429]">

                                                <div
                                                    class="grid grid-cols-1 gap-2
                                               sm:grid-cols-2">

                                                    <p>

                                                        {{ __('messages.sent_amount') }}:

                                                        <strong
                                                            class="text-[#2B2118]
                                                       dark:text-[#F7F1E8]">
                                                            {{ number_format($movement['from_amount'], 2) }}

                                                            {{ $movement['from_currency'] }}
                                                        </strong>

                                                    </p>


                                                    <p>

                                                        {{ __('messages.received_amount') }}:

                                                        <strong
                                                            class="text-[#2B2118]
                                                       dark:text-[#F7F1E8]">
                                                            {{ number_format($movement['to_amount'], 2) }}

                                                            {{ $movement['to_currency'] }}
                                                        </strong>

                                                    </p>

                                                </div>


                                                @if ($movement['exchange_rate'])
                                                    <p class="mt-2">

                                                        <i
                                                            class="fa-solid
                                                       fa-arrow-right-arrow-left
                                                       me-1"></i>

                                                        {{ __('messages.exchange_rate') }}:

                                                        <strong
                                                            class="text-[#6B4A2D]
                                                       dark:text-[#D8B77A]">
                                                            1
                                                            {{ $movement['from_currency'] }}

                                                            ≈

                                                            {{ number_format($movement['exchange_rate'], 4) }}

                                                            {{ $movement['to_currency'] }}
                                                        </strong>

                                                    </p>
                                                @endif

                                            </div>
                                        @endif

                                    </div>
                                @endif



                                {{-- Original Transaction --}}
                                @if (
                                    !$isTransfer &&
                                        $movement['original_amount'] &&
                                        $movement['original_currency'] &&
                                        $movement['original_currency'] !== $movement['currency']
                                )
                                    <p
                                        class="mt-2 text-xs
                                   text-[#8A7A68]
                                   dark:text-[#C4B3A1]">

                                        {{ __('messages.original_transaction') }}:

                                        <strong>
                                            {{ number_format($movement['original_amount'], 2) }}

                                            {{ $movement['original_currency'] }}
                                        </strong>

                                    </p>
                                @endif

                            </div>

                        </div>



                        {{-- Account Movement Amount --}}
                        <div class="shrink-0 sm:text-end">

                            <p
                                class="text-lg font-bold
                           {{ $isIncoming ? 'text-[#4F7A5A] dark:text-[#7EAA89]' : 'text-[#B85C5C] dark:text-[#DF7E7E]' }}">

                                {{ $isIncoming ? '+' : '-' }}

                                {{ number_format($movement['amount'], 2) }}

                                {{ $movement['currency'] }}

                            </p>


                            <p
                                class="mt-1 text-xs
                           text-[#8A7A68]
                           dark:text-[#C4B3A1]">

                                @switch($movement['movement_type'])
                                    @case('transfer_in')
                                        {{ __('messages.incoming_transfer') }}
                                    @break

                                    @case('transfer_out')
                                        {{ __('messages.outgoing_transfer') }}
                                    @break

                                    @case('saving')
                                        {{ __('messages.saving_movement') }}
                                    @break

                                    @default
                                        {{ __('messages.account_balance_movement') }}
                                @endswitch

                            </p>

                        </div>

                    </div>


                    @empty

                        <div class="px-5 py-12 text-center">

                            <div
                                class="mx-auto flex h-12 w-12
                       items-center justify-center
                       rounded-full
                       bg-gray-100
                       dark:bg-gray-800">

                                <i
                                    class="fa-solid fa-arrow-right-arrow-left
                           text-gray-500
                           dark:text-gray-400"></i>

                            </div>


                            <p
                                class="mt-4 text-sm
                       text-gray-500
                       dark:text-gray-400">

                                {{ __('messages.no_account_movements') }}

                            </p>

                        </div>
                    @endforelse

                </div>

            </div>

        </div>
        <div
    id="statementModal"
    class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/40 px-4"
>

    <div
        class="w-full max-w-lg rounded-2xl
               border border-[#E8DDCF]
               bg-[#FFFDF9]
               p-6 shadow-xl

               dark:border-[#463429]
               dark:bg-[#241B16]"
    >

        <div class="mb-5 flex items-center justify-between">

            <div>

                <h3 class="text-lg font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ __('messages.account_statement') }}
                </h3>

                <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.account_statement_description') }}
                </p>

            </div>

            <button
                type="button"
                onclick="document.getElementById('statementModal').classList.add('hidden')"
                class="flex h-9 w-9 items-center justify-center
                       rounded-lg
                       text-[#8A7A68]
                       hover:bg-[#F3E8D8]
                       dark:text-[#C4B3A1]
                       dark:hover:bg-[#30231B]"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <form
            method="GET"
            action="{{ route('accounts.statement', $account) }}"
            class="space-y-5"
        >

            {{-- From Date --}}
            <div>

                <label
                    class="mb-2 block text-sm font-medium
                           text-[#3E342C]
                           dark:text-[#E7DDD2]"
                >
                    {{ __('messages.from_date') }}
                </label>

                <input
                    type="date"
                    name="from"
                    required
                    onclick="this.showPicker()"

                    class="h-12 w-full rounded-xl border
                           border-[#D8CEC2]
                           bg-[#FFFDF9]
                           px-4
                           text-sm text-[#2B2118]
                           outline-none
                           focus:border-[#B88945]

                           dark:border-[#594236]
                           dark:bg-[#2A1F18]
                           dark:text-[#F7F1E8]"
                >

            </div>


            {{-- To Date --}}
            <div>

                <label
                    class="mb-2 block text-sm font-medium
                           text-[#3E342C]
                           dark:text-[#E7DDD2]"
                >
                    {{ __('messages.to_date') }}
                </label>

                <input
                    type="date"
                    name="to"
                    required
                    onclick="this.showPicker()"

                    class="h-12 w-full rounded-xl border
                           border-[#D8CEC2]
                           bg-[#FFFDF9]
                           px-4
                           text-sm text-[#2B2118]
                           outline-none
                           focus:border-[#B88945]

                           dark:border-[#594236]
                           dark:bg-[#2A1F18]
                           dark:text-[#F7F1E8]"
                >

            </div>


            <div
                class="flex justify-end gap-3
                       border-t border-[#EEE2D5]
                       pt-5
                       dark:border-[#463429]"
            >

                <button
                    type="button"
                    onclick="document.getElementById('statementModal').classList.add('hidden')"
                    class="rounded-xl border
                           border-[#D8CEC2]
                           px-4 py-2.5
                           text-sm font-medium
                           text-[#6B4A2D]

                           hover:bg-[#F3E8D8]

                           dark:border-[#594236]
                           dark:text-[#D8B77A]"
                >
                    {{ __('messages.cancel') }}
                </button>


                <button
                    type="submit"
                    class="rounded-xl
                           px-4 py-2.5
                           text-sm font-medium
                           text-white"

                    style="
                        background:
                            linear-gradient(
                                135deg,
                                #5E4029,
                                #8A6238
                            );
                    "
                >
                    <i class="fa-solid fa-file-lines me-2"></i>

                    {{ __('messages.generate_statement') }}
                </button>

            </div>

        </form>

    </div>

</div>
    @endsection
