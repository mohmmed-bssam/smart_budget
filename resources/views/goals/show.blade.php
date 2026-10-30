@extends('layouts.app')

@section('content')

@php
    $progressWidth = min($percentage, 100);

    $returnAccounts = $accounts->where(
        'currency',
        $goal->currency
    );
@endphp


<div
    class="space-y-6"

    x-data="{
        selectedAccount: '{{ old('account_id') }}',

        accounts: @js(
            $accounts->mapWithKeys(function ($account) {
                return [
                    (string) $account->id => [
                        'currency' => $account->currency,
                        'balance' => (float) $account->balance,
                    ]
                ];
            })
        ),

        get selectedAccountData() {
            return this.accounts[this.selectedAccount] ?? null;
        },

        get differentCurrency() {
            return this.selectedAccountData
                && this.selectedAccountData.currency !== '{{ $goal->currency }}';
        }
    }"
>

    {{-- Header --}}
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



    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Target --}}
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


        {{-- Saved --}}
        <div
            class="rounded-2xl border p-5
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl
                       bg-[#EEF4EF] text-[#4F7A5A]
                       dark:bg-[#233329] dark:text-[#7EAA89]"
            >
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


        {{-- Remaining --}}
        <div
            class="rounded-2xl border p-5
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl
                       bg-[#F8F3EC] text-[#6B4A2D]
                       dark:bg-[#30231B] dark:text-[#D8B77A]"
            >
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



    {{-- Progress --}}
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


        <div
            class="mt-5 h-4 w-full overflow-hidden rounded-full
                   bg-[#F3E8D8] dark:bg-[#30231B]"
        >

            <div
                class="h-full rounded-full
                    {{ $goal->status === 'completed'
                        ? 'bg-[#4F7A5A]'
                        : 'bg-[#B88945]' }}"
                style="width: {{ $progressWidth }}%;"
            >
            </div>

        </div>


        @if($goal->status === 'completed')

            <div
                class="mt-5 rounded-xl
                       bg-[#EEF4EF] px-4 py-3
                       text-sm text-[#4F7A5A]
                       dark:bg-[#233329]
                       dark:text-[#7EAA89]"
            >

                <i class="fa-solid fa-circle-check me-2"></i>

                {{ __('messages.goal_completed_message') }}

            </div>

        @endif


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



    {{-- Add Saving --}}
    @if($goal->status === 'active')

        <div
            class="rounded-2xl border p-6
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div class="mb-6">

                <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ __('messages.add_saving') }}
                </h3>

                <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.add_saving_description') }}
                </p>

            </div>


            @if($accounts->isEmpty())

                <div
                    class="rounded-xl bg-[#F8EAEA]
                           px-4 py-3 text-sm
                           text-[#B85C5C]
                           dark:bg-[#3A2222]
                           dark:text-[#DF7E7E]"
                >

                    {{ __('messages.no_accounts_for_saving') }}

                    <a
                        href="{{ route('accounts.create') }}"
                        class="ms-1 font-semibold underline"
                    >
                        {{ __('messages.add_account') }}
                    </a>

                </div>

            @else

                <form
                    method="POST"
                    action="{{ route('goals.savings.store', $goal) }}"
                    class="space-y-6"
                >

                    @csrf


                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- Account --}}
                        <div>

                            <label
                                class="mb-2 block text-sm font-medium
                                       text-[#3E342C]
                                       dark:text-[#E7DDD2]"
                            >
                                {{ __('messages.from_account') }}
                            </label>


                            <select
                                name="account_id"
                                x-model="selectedAccount"

                                class="h-12 w-full rounded-xl border
                                       border-[#D8CEC2] bg-[#FFFDF9]
                                       px-4 text-sm text-[#2B2118]
                                       outline-none
                                       focus:border-[#B88945]

                                       dark:border-[#594236]
                                       dark:bg-[#2A1F18]
                                       dark:text-[#F7F1E8]"
                            >

                                <option value="">
                                    {{ __('messages.select_account') }}
                                </option>


                                @foreach($accounts as $account)

                                    <option
                                        value="{{ $account->id }}"
                                    >

                                        {{ $account->name[app()->getLocale()]
                                            ?? $account->name['en']
                                            ?? '' }}

                                        -

                                        {{ number_format($account->balance, 2) }}

                                        {{ $account->currency }}

                                    </option>

                                @endforeach

                            </select>


                            @error('account_id')

                                <p class="mt-1 text-sm text-[#B85C5C]">
                                    {{ $message }}
                                </p>

                            @enderror


                            {{-- Currency notice --}}
                            <div
                                x-show="differentCurrency"
                                x-cloak

                                class="mt-2 rounded-lg
                                       bg-[#F8F3EC]
                                       px-3 py-2
                                       text-xs text-[#8A6238]

                                       dark:bg-[#30231B]
                                       dark:text-[#D8B77A]"
                            >

                                <i class="fa-solid fa-arrows-rotate me-1"></i>

                                {{ __('messages.saving_currency_conversion_notice') }}

                                <strong>
                                    {{ $goal->currency }}
                                </strong>

                                <template x-if="selectedAccountData">

                                    <span>
                                        →
                                        <strong x-text="selectedAccountData.currency"></strong>
                                    </span>

                                </template>

                            </div>

                        </div>



                        {{-- Amount --}}
                        <div>

                            <label
                                class="mb-2 block text-sm font-medium
                                       text-[#3E342C]
                                       dark:text-[#E7DDD2]"
                            >
                                {{ __('messages.saving_amount') }}

                                ({{ $goal->currency }})
                            </label>


                            <input
                                type="number"
                                name="amount"

                                min="0.01"
                                max="{{ $remaining }}"
                                step="0.01"

                                value="{{ old('amount') }}"

                                class="h-12 w-full rounded-xl border
                                       border-[#D8CEC2] bg-[#FFFDF9]
                                       px-4 text-sm text-[#2B2118]
                                       outline-none
                                       focus:border-[#B88945]

                                       dark:border-[#594236]
                                       dark:bg-[#2A1F18]
                                       dark:text-[#F7F1E8]"
                            >


                            <p class="mt-1 text-xs text-[#8A7A68] dark:text-[#C4B3A1]">

                                {{ __('messages.maximum_saving') }}:

                                {{ number_format($remaining, 2) }}

                                {{ $goal->currency }}

                            </p>


                            @error('amount')

                                <p class="mt-1 text-sm text-[#B85C5C]">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>



                        {{-- Date --}}
                        <div>

                            <label
                                class="mb-2 block text-sm font-medium
                                       text-[#3E342C]
                                       dark:text-[#E7DDD2]"
                            >
                                {{ __('messages.saving_date') }}
                            </label>


                            <div class="relative">

                                <input
                                    id="saving_date"
                                    type="date"
                                    name="date"

                                    max="{{ now()->toDateString() }}"

                                    onclick="this.showPicker()"

                                    value="{{ old(
                                        'date',
                                        now()->toDateString()
                                    ) }}"

                                    class="h-12 w-full rounded-xl border
                                           border-[#D8CEC2] bg-[#FFFDF9]
                                           px-4 pe-12
                                           text-sm text-[#2B2118]
                                           outline-none
                                           focus:border-[#B88945]

                                           dark:border-[#594236]
                                           dark:bg-[#2A1F18]
                                           dark:text-[#F7F1E8]"
                                >


                                <button
                                    type="button"

                                    onclick="
                                        document
                                            .getElementById('saving_date')
                                            .showPicker()
                                    "

                                    class="absolute inset-y-0 end-0
                                           flex items-center pe-4
                                           text-[#8A6238]
                                           hover:text-[#6B4A2D]

                                           dark:text-[#D8B77A]
                                           dark:hover:text-[#E3C38F]"
                                >

                                    <i class="fa-regular fa-calendar-days"></i>

                                </button>

                            </div>


                            @error('date')

                                <p class="mt-1 text-sm text-[#B85C5C]">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>



                    {{-- Note --}}
                    <div>

                        <label
                            class="mb-2 block text-sm font-medium
                                   text-[#3E342C]
                                   dark:text-[#E7DDD2]"
                        >
                            {{ __('messages.note') }}
                        </label>


                        <textarea
                            name="note"
                            rows="3"

                            class="w-full rounded-xl border
                                   border-[#D8CEC2] bg-[#FFFDF9]
                                   px-4 py-3
                                   text-sm text-[#2B2118]
                                   outline-none
                                   focus:border-[#B88945]

                                   dark:border-[#594236]
                                   dark:bg-[#2A1F18]
                                   dark:text-[#F7F1E8]"
                        >{{ old('note') }}</textarea>


                        @error('note')

                            <p class="mt-1 text-sm text-[#B85C5C]">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    <div
                        class="flex justify-end
                               border-t pt-5
                               border-[#EEE2D5]
                               dark:border-[#463429]"
                    >

                        <button
                            type="submit"

                            class="inline-flex items-center
                                   rounded-xl
                                   px-5 py-2.5
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

                            <i class="fa-solid fa-piggy-bank me-2"></i>

                            {{ __('messages.add_saving') }}

                        </button>

                    </div>

                </form>

            @endif

        </div>

    @endif



    {{-- Savings History --}}
    <div
        class="rounded-2xl border
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div
            class="border-b p-5
                   border-[#EEE2D5]
                   dark:border-[#463429]"
        >

            <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ __('messages.savings_history') }}
            </h3>

        </div>


        <div class="divide-y divide-[#EEE2D5] dark:divide-[#463429]">

            @forelse($goal->savings as $saving)

                <div class="p-5">

                    <div
                        class="flex flex-col gap-4
                               sm:flex-row
                               sm:items-center
                               sm:justify-between"
                    >

                        <div>

                            <p
                                class="text-lg font-semibold
                                       text-[#4F7A5A]
                                       dark:text-[#7EAA89]"
                            >

                                + {{ number_format($saving->amount, 2) }}

                                {{ $saving->currency }}

                            </p>


                            @if($saving->account)

                                <p
                                    class="mt-1 text-sm
                                           text-[#8A7A68]
                                           dark:text-[#C4B3A1]"
                                >

                                    {{ __('messages.from_account') }}:

                                    {{ $saving->account->name[app()->getLocale()]
                                        ?? $saving->account->name['en']
                                        ?? '-' }}

                                </p>

                            @endif


                            @if(
                                $saving->source_currency
                                !==
                                $saving->currency
                            )

                                <p
                                    class="mt-1 text-xs
                                           text-[#8A7A68]
                                           dark:text-[#C4B3A1]"
                                >

                                    {{ __('messages.deducted_amount') }}:

                                    {{ number_format(
                                        $saving->source_amount,
                                        2
                                    ) }}

                                    {{ $saving->source_currency }}

                                </p>

                            @endif


                            @if($saving->note)

                                <p
                                    class="mt-2 text-sm
                                           text-[#8A7A68]
                                           dark:text-[#C4B3A1]"
                                >
                                    {{ $saving->note }}
                                </p>

                            @endif

                        </div>



                        <div class="flex flex-col items-start gap-2 sm:items-end">

                            <p
                                class="text-sm
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >

                                <i class="fa-regular fa-calendar me-1"></i>

                                {{ $saving->date?->format('Y-m-d') }}

                            </p>


                            {{-- Delete Saving --}}
                            @if($returnAccounts->isNotEmpty())

                                <form
                                    method="POST"

                                    action="{{ route(
                                        'savings.destroy',
                                        $saving
                                    ) }}"

                                    class="flex flex-wrap
                                           items-center gap-2"

                                    onsubmit="
                                        return confirm(
                                            @js(
                                                __('messages.delete_saving_confirmation')
                                            )
                                        )
                                    "
                                >

                                    @csrf
                                    @method('DELETE')


                                    <select
                                        name="return_account_id"

                                        required

                                        class="h-9 rounded-lg border
                                               border-[#D8CEC2]
                                               bg-[#FFFDF9]
                                               px-3
                                               text-xs
                                               text-[#2B2118]

                                               dark:border-[#594236]
                                               dark:bg-[#2A1F18]
                                               dark:text-[#F7F1E8]"
                                    >

                                        <option value="">
                                            {{ __('messages.return_to_account') }}
                                        </option>


                                        @foreach($returnAccounts as $returnAccount)

                                            <option
                                                value="{{ $returnAccount->id }}"
                                            >

                                                {{ $returnAccount->name[app()->getLocale()]
                                                    ?? $returnAccount->name['en']
                                                    ?? '' }}

                                                -

                                                {{ $returnAccount->currency }}

                                            </option>

                                        @endforeach

                                    </select>


                                    <button
                                        type="submit"

                                        class="rounded-lg border
                                               border-[#E6BDBD]
                                               px-3 py-2
                                               text-xs font-medium
                                               text-[#B85C5C]

                                               hover:bg-[#F8EAEA]

                                               dark:border-[#674040]
                                               dark:text-[#DF7E7E]
                                               dark:hover:bg-[#3A2222]"
                                    >

                                        {{ __('messages.delete') }}

                                    </button>

                                </form>

                            @else

                                <p
                                    class="text-xs
                                           text-[#B85C5C]
                                           dark:text-[#DF7E7E]"
                                >

                                    {{ __('messages.no_return_account') }}

                                </p>

                            @endif

                        </div>

                    </div>

                </div>


            @empty

                <div
                    class="p-8 text-center
                           text-sm text-[#8A7A68]
                           dark:text-[#C4B3A1]"
                >

                    <div
                        class="mx-auto mb-3
                               flex h-12 w-12
                               items-center justify-center
                               rounded-xl
                               bg-[#F3E8D8]
                               text-[#6B4A2D]

                               dark:bg-[#30231B]
                               dark:text-[#D8B77A]"
                    >

                        <i class="fa-solid fa-piggy-bank"></i>

                    </div>

                    {{ __('messages.no_savings_found') }}

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
