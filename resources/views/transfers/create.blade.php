@extends('layouts.app')
@section('title', __('messages.create_transfer_title'))

@section('content')

<div class="space-y-6">

    <div>

        <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
            {{ __('messages.new_transfer') }}
        </h2>

        <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
            {{ __('messages.new_transfer_description') }}
        </p>

    </div>


    <div
        class="rounded-2xl border p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <form
            method="POST"
            action="{{ route('transfers.store') }}"
            class="space-y-6"

            x-data="{
                fromAccount: '{{ old('from_account_id', $selectedFromAccountId ?? '') }}',
                toAccount: '{{ old('to_account_id') }}',

                accountsAreSame() {
                    return this.fromAccount
                        && this.toAccount
                        && this.fromAccount === this.toAccount;
                }
            }"
        >

            @csrf


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- From Account --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.from_account') }}
                    </label>


                    <select
                        name="from_account_id"
                        x-model="fromAccount"

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


                    @error('from_account_id')

                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- To Account --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.to_account') }}
                    </label>


                    <select
                        name="to_account_id"
                        x-model="toAccount"

                        :class="
                            accountsAreSame()
                                ? 'border-[#B85C5C] dark:border-[#DF7E7E]'
                                : ''
                        "

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


                    {{-- Immediate validation --}}
                    <p
                        x-show="accountsAreSame()"
                        x-cloak
                        class="mt-1 text-sm text-[#B85C5C]
                               dark:text-[#DF7E7E]"
                    >
                        {{ __('messages.transfer_accounts_must_be_different') }}
                    </p>


                    @error('to_account_id')

                        <p
                            x-show="!accountsAreSame()"
                            class="mt-1 text-sm text-[#B85C5C]"
                        >
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Amount --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.transfer_amount') }}
                    </label>


                    <input
                        type="number"
                        name="from_amount"

                        min="0.01"
                        step="0.01"

                        value="{{ old('from_amount') }}"

                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none
                               focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >


                    @error('from_amount')

                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Transfer Date --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.transfer_date') }}
                    </label>


                    <div class="relative">

                        <input
                            id="transfer_date"

                            type="date"

                            name="transfer_date"

                            onclick="this.showPicker()"

                            value="{{ old(
                                'transfer_date',
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
                                    .getElementById('transfer_date')
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


                    @error('transfer_date')

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
                    rows="4"

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


            {{-- Actions --}}
            <div
                class="flex justify-end gap-3
                       border-t pt-6
                       border-[#EEE2D5]
                       dark:border-[#463429]"
            >

                <a
                    href="{{ route('transfers.index') }}"

                    class="rounded-xl border
                           border-[#D8CEC2]
                           px-5 py-2.5
                           text-sm font-medium
                           text-[#6B4A2D]

                           hover:bg-[#F3E8D8]

                           dark:border-[#594236]
                           dark:text-[#D8B77A]
                           dark:hover:bg-[#30231B]"
                >
                    {{ __('messages.cancel') }}
                </a>


                <button
                    type="submit"

                    :disabled="accountsAreSame()"

                    :class="
                        accountsAreSame()
                            ? 'opacity-50 cursor-not-allowed'
                            : ''
                    "

                    class="rounded-xl
                           px-5 py-2.5
                           text-sm font-medium
                           text-white
                           transition"

                    style="
                        background:
                            linear-gradient(
                                135deg,
                                #5E4029,
                                #8A6238
                            );
                    "
                >
                    <i class="fa-solid fa-right-left me-2"></i>

                    {{ __('messages.transfer_money') }}
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
