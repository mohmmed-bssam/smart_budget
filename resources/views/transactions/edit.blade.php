@extends('layouts.app')
@section('title', __('messages.edit_transaction_title'))

@section('content')

<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
            {{ __('messages.edit_transaction') }}
        </h2>
    </div>


    <div
        class="rounded-2xl border p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <form
            method="POST"
            action="{{ route('transactions.update', $transaction) }}"
            class="space-y-6"

            x-data="{
                selectedAccount: '{{ old('account_id', $transaction->account_id) }}',

                transactionCurrency:
                    '{{ old('currency', $transaction->currency) }}',

                transactionAmount:
                    '{{ old('amount', $transaction->amount) }}',

                loadingRate: false,
                conversionError: '',
                convertedAmount: null,
                exchangeRate: null,
                inverseRate: null,

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

                get currenciesAreDifferent() {
                    return this.selectedAccountData
                        && this.transactionCurrency
                        && this.selectedAccountData.currency
                            !== this.transactionCurrency;
                },

                async updateConversion() {

                    this.convertedAmount = null;
                    this.exchangeRate = null;
                    this.inverseRate = null;
                    this.conversionError = '';

                    if (
                        !this.selectedAccount
                        || !this.transactionCurrency
                        || !this.transactionAmount
                        || Number(this.transactionAmount) <= 0
                    ) {
                        return;
                    }

                    /*
                     * إذا كانت نفس العملة
                     */
                    if (
                        this.selectedAccountData
                        &&
                        this.selectedAccountData.currency
                            === this.transactionCurrency
                    ) {
                        this.convertedAmount =
                            Number(this.transactionAmount);

                        this.exchangeRate = 1;
                        this.inverseRate = 1;

                        return;
                    }

                    this.loadingRate = true;

                    try {

                        const params =
                            new URLSearchParams({
                                account_id:
                                    this.selectedAccount,

                                currency:
                                    this.transactionCurrency,

                                amount:
                                    this.transactionAmount,
                            });


                        const response =
                            await fetch(
                                '{{ route('transactions.exchange-preview') }}'
                                + '?'
                                + params.toString(),
                                {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                }
                            );


                        const data =
                            await response.json();


                        if (!response.ok) {
                            throw new Error(
                                data.message
                                ||
                                '{{ __('messages.currency_service_unavailable') }}'
                            );
                        }


                        this.convertedAmount =
                            data.converted_amount;

                        this.exchangeRate =
                            data.rate;

                        this.inverseRate =
                            data.inverse_rate;

                    } catch (error) {

                        this.conversionError =
                            error.message;

                    } finally {

                        this.loadingRate = false;
                    }
                }
            }"

            x-init="$nextTick(() => updateConversion())"
        >

            @csrf
            @method('PUT')


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Title --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.title') }}
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $transaction->title) }}"

                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                    @error('title')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Type --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.type') }}
                    </label>

                    <select
                        id="transactionType"
                        name="type"

                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                        <option
                            value="income"
                            @selected(
                                old(
                                    'type',
                                    $transaction->type
                                ) === 'income'
                            )
                        >
                            {{ __('messages.income') }}
                        </option>


                        <option
                            value="expense"
                            @selected(
                                old(
                                    'type',
                                    $transaction->type
                                ) === 'expense'
                            )
                        >
                            {{ __('messages.expense') }}
                        </option>

                    </select>


                    @error('type')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Account --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.account') }}
                    </label>


                    <select
                        name="account_id"

                        x-model="selectedAccount"

                        @change="
                            updateConversion()
                        "

                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                        @foreach($accounts as $account)

                            <option
                                value="{{ $account->id }}"
                            >

                                {{ $account->name[app()->getLocale()]
                                    ?? $account->name['en']
                                    ?? '' }}

                                -

                                {{ number_format(
                                    $account->balance,
                                    2
                                ) }}

                                {{ $account->currency }}

                            </option>

                        @endforeach

                    </select>


                    <template x-if="selectedAccountData">

                        <p
                            class="mt-1 text-xs
                                   text-[#8A7A68]
                                   dark:text-[#C4B3A1]"
                        >

                            {{ __('messages.account_currency') }}:

                            <strong
                                x-text="
                                    selectedAccountData.currency
                                "
                            ></strong>

                        </p>

                    </template>


                    @error('account_id')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Category --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.category') }}
                    </label>


                    <select
                        id="categorySelect"
                        name="category_id"

                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                data-type="{{ $category->type }}"

                                @selected(
                                    old(
                                        'category_id',
                                        $transaction->category_id
                                    ) == $category->id
                                )
                            >

                                {{ $category->name[app()->getLocale()]
                                    ?? $category->name['en']
                                    ?? '' }}

                            </option>

                        @endforeach

                    </select>


                    @error('category_id')
                        <p class="mt-1 text-sm text-[#B85C5C]">
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

                        {{ __('messages.amount') }}

                        <template x-if="transactionCurrency">

                            <span>
                                (
                                <span
                                    x-text="
                                        transactionCurrency
                                    "
                                ></span>
                                )
                            </span>

                        </template>

                    </label>


                    <input
                        type="number"
                        name="amount"
                        step="0.01"
                        min="0.01"

                        x-model="transactionAmount"

                        @input.debounce.500ms="
                            updateConversion()
                        "

                        value="{{ old(
                            'amount',
                            $transaction->amount
                        ) }}"

                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >


                    @error('amount')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Transaction Currency --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.transaction_currency') }}
                    </label>


                    <select
                        name="currency"

                        x-model="transactionCurrency"

                        @change="
                            updateConversion()
                        "

                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                        @foreach([
                            'ILS',
                            'USD',
                            'EUR',
                            'JOD',
                            'GBP'
                        ] as $currency)

                            <option
                                value="{{ $currency }}"
                            >
                                {{ $currency }}
                            </option>

                        @endforeach

                    </select>


                    @error('currency')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror


                    {{-- Exchange Preview --}}
                    <div class="mt-3">

                        {{-- Loading --}}
                        <div
                            x-show="loadingRate"
                            x-cloak

                            class="rounded-xl
                                   bg-[#F8F3EC]
                                   px-4 py-3
                                   text-sm text-[#8A6238]

                                   dark:bg-[#30231B]
                                   dark:text-[#D8B77A]"
                        >

                            <i
                                class="fa-solid
                                       fa-spinner
                                       fa-spin
                                       me-2"
                            ></i>

                            {{ __('messages.checking_exchange_rate') }}

                        </div>


                        {{-- Different Currency --}}
                        <div
                            x-show="
                                !loadingRate
                                &&
                                currenciesAreDifferent
                                &&
                                convertedAmount !== null
                            "

                            x-cloak

                            class="rounded-xl border
                                   border-[#E8DDCF]
                                   bg-[#F8F3EC]
                                   p-4

                                   dark:border-[#463429]
                                   dark:bg-[#30231B]"
                        >

                            <div class="flex items-start gap-3">

                                <div
                                    class="flex h-10 w-10
                                           shrink-0
                                           items-center
                                           justify-center
                                           rounded-xl

                                           bg-[#F3E8D8]
                                           text-[#6B4A2D]

                                           dark:bg-[#3A2A20]
                                           dark:text-[#D8B77A]"
                                >

                                    <i
                                        class="fa-solid
                                               fa-arrow-right-arrow-left"
                                    ></i>

                                </div>


                                <div>

                                    <p
                                        class="text-sm font-medium
                                               text-[#2B2118]
                                               dark:text-[#F7F1E8]"
                                    >
                                        {{ __('messages.estimated_account_amount') }}
                                    </p>


                                    <p
                                        class="mt-1
                                               text-lg font-bold
                                               text-[#6B4A2D]
                                               dark:text-[#D8B77A]"
                                    >

                                        <span
                                            x-text="
                                                convertedAmount
                                                !== null
                                                    ? Number(
                                                        convertedAmount
                                                    ).toFixed(2)
                                                    : ''
                                            "
                                        ></span>

                                        <span
                                            x-text="
                                                selectedAccountData
                                                    ? selectedAccountData.currency
                                                    : ''
                                            "
                                        ></span>

                                    </p>


                                    <p
                                        class="mt-2
                                               text-xs
                                               text-[#8A7A68]
                                               dark:text-[#C4B3A1]"
                                    >

                                        {{ __('messages.current_exchange_rate') }}:

                                        1

                                        <span
                                            x-text="
                                                selectedAccountData
                                                    ? selectedAccountData.currency
                                                    : ''
                                            "
                                        ></span>

                                        ≈

                                        <strong
                                            x-text="
                                                inverseRate
                                                    ? Number(
                                                        inverseRate
                                                    ).toFixed(4)
                                                    : ''
                                            "
                                        ></strong>

                                        <span
                                            x-text="
                                                transactionCurrency
                                            "
                                        ></span>

                                    </p>


                                    <p
                                        class="mt-2
                                               text-xs
                                               text-[#8A7A68]
                                               dark:text-[#C4B3A1]"
                                    >

                                        <i
                                            class="fa-solid
                                                   fa-circle-info
                                                   me-1"
                                        ></i>

                                        {{ __('messages.exchange_rate_notice') }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Same Currency --}}
                        <div
                            x-show="
                                !loadingRate
                                &&
                                selectedAccountData
                                &&
                                transactionCurrency
                                &&
                                !currenciesAreDifferent
                            "

                            x-cloak

                            class="rounded-xl
                                   bg-[#EEF4EF]
                                   px-4 py-3
                                   text-xs text-[#4F7A5A]

                                   dark:bg-[#233329]
                                   dark:text-[#7EAA89]"
                        >

                            <i
                                class="fa-solid
                                       fa-circle-check
                                       me-1"
                            ></i>

                            {{ __('messages.same_currency_no_conversion') }}

                        </div>


                        {{-- Error --}}
                        <div
                            x-show="conversionError"
                            x-cloak

                            class="rounded-xl
                                   bg-[#F8EAEA]
                                   px-4 py-3
                                   text-sm text-[#B85C5C]

                                   dark:bg-[#3A2222]
                                   dark:text-[#DF7E7E]"
                        >

                            <i
                                class="fa-solid
                                       fa-triangle-exclamation
                                       me-2"
                            ></i>

                            <span
                                x-text="
                                    conversionError
                                "
                            ></span>

                        </div>

                    </div>

                </div>


                {{-- Date --}}
                <div>

                    <label
                        class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]"
                    >
                        {{ __('messages.date') }}
                    </label>


                    <div class="relative">

                        <input
                            id="transaction_date"
                            type="date"
                            name="transaction_date"

                            onclick="
                                this.showPicker()
                            "

                            value="{{ old(
                                'transaction_date',
                                $transaction->transaction_date?->format('Y-m-d')
                            ) }}"

                            class="h-12 w-full rounded-xl border
                                   border-[#D8CEC2] bg-[#FFFDF9]
                                   px-4 pe-12 text-sm
                                   text-[#2B2118]
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
                                    .getElementById(
                                        'transaction_date'
                                    )
                                    .showPicker()
                            "

                            class="absolute inset-y-0
                                   end-0
                                   flex items-center
                                   pe-4
                                   text-[#8A6238]

                                   hover:text-[#6B4A2D]

                                   dark:text-[#D8B77A]
                                   dark:hover:text-[#E3C38F]"
                        >

                            <i
                                class="fa-regular
                                       fa-calendar-days"
                            ></i>

                        </button>

                    </div>


                    @error('transaction_date')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- Description --}}
            <div>

                <label
                    class="mb-2 block text-sm font-medium
                           text-[#3E342C]
                           dark:text-[#E7DDD2]"
                >
                    {{ __('messages.description') }}
                </label>

                <textarea
                    name="description"
                    rows="4"

                    class="w-full rounded-xl border
                           border-[#D8CEC2] bg-[#FFFDF9]
                           px-4 py-3 text-sm
                           text-[#2B2118]
                           outline-none
                           focus:border-[#B88945]

                           dark:border-[#594236]
                           dark:bg-[#2A1F18]
                           dark:text-[#F7F1E8]"
                >{{ old(
                    'description',
                    $transaction->description
                ) }}</textarea>


                @error('description')
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
                    href="{{ route('transactions.index') }}"

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

                    class="rounded-xl
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
                    {{ __('messages.save_changes') }}
                </button>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const typeSelect =
            document.getElementById(
                'transactionType'
            );

        const categorySelect =
            document.getElementById(
                'categorySelect'
            );


        if (
            !typeSelect
            ||
            !categorySelect
        ) {
            return;
        }


        const options =
            Array.from(
                categorySelect.options
            );


        function filterCategories() {

            const selectedType =
                typeSelect.value;


            options.forEach(
                (option) => {

                    const categoryType =
                        option.dataset.type;


                    if (!categoryType) {
                        return;
                    }


                    option.hidden =
                        categoryType
                        !== selectedType;
                }
            );


            const selectedOption =
                categorySelect.options[
                    categorySelect.selectedIndex
                ];


            if (
                selectedOption
                &&
                selectedOption.dataset.type
                &&
                selectedOption.dataset.type
                    !== selectedType
            ) {
                categorySelect.value = '';
            }
        }


        typeSelect.addEventListener(
            'change',
            filterCategories
        );


        filterCategories();
    }
);

</script>

@endsection
