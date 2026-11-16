@extends('layouts.app')
@section('title', __('messages.create_transaction_title'))

@section('content')
    <div class="space-y-6">

        <div>

            <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ __('messages.add_transaction') }}
            </h2>

            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.add_transaction_description') }}
            </p>

        </div>


        <div
            class="rounded-2xl border p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]">

            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-6" x-data="{
                selectedAccount: '{{ old('account_id') }}',
                transactionCurrency: '{{ old('currency') }}',
                transactionAmount: '{{ old('amount') }}',

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
            ],
        ];
    }),
),

                get selectedAccountData() {
                    return this.accounts[this.selectedAccount] ?? null;
                },

                get currenciesAreDifferent() {
                    return this.selectedAccountData &&
                        this.transactionCurrency &&
                        this.selectedAccountData.currency !==
                        this.transactionCurrency;
                },

                async updateConversion() {

                    this.convertedAmount = null;
                    this.exchangeRate = null;
                    this.inverseRate = null;
                    this.conversionError = '';

                    if (
                        !this.selectedAccount ||
                        !this.transactionCurrency ||
                        !this.transactionAmount ||
                        Number(this.transactionAmount) <= 0
                    ) {
                        return;
                    }

                    /*
                     * نفس العملة
                     */
                    if (
                        this.selectedAccountData &&
                        this.selectedAccountData.currency ===
                        this.transactionCurrency
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
                                account_id: this.selectedAccount,

                                currency: this.transactionCurrency,

                                amount: this.transactionAmount,
                            });


                        const response =
                            await fetch(
                                '{{ route('transactions.exchange-preview') }}' +
                                '?' +
                                params.toString(), {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                }
                            );


                        const data =
                            await response.json();


                        if (!response.ok) {
                            throw new Error(
                                data.message ||
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
            }">

                @csrf


                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Title --}}
                    <div>

                        <label
                            class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]">
                            {{ __('messages.title') }}
                        </label>

                        <input type="text" name="title" value="{{ old('title') }}"
                            class="h-12 w-full rounded-xl border
                               border-[#D8CEC2]
                               bg-[#FFFDF9]
                               px-4
                               text-sm text-[#2B2118]
                               outline-none
                               focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]">

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
                               dark:text-[#E7DDD2]">
                            {{ __('messages.type') }}
                        </label>

                        <select id="transactionType" name="type"
                            class="h-12 w-full rounded-xl border
                               border-[#D8CEC2]
                               bg-[#FFFDF9]
                               px-4
                               text-sm text-[#2B2118]
                               outline-none
                               focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]">

                            <option value="">
                                {{ __('messages.select_transaction_type') }}
                            </option>

                            <option value="income" @selected(old('type') === 'income')>
                                {{ __('messages.income') }}
                            </option>

                            <option value="expense" @selected(old('type') === 'expense')>
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
                               dark:text-[#E7DDD2]">
                            {{ __('messages.account') }}
                        </label>

                        <select name="account_id" x-model="selectedAccount" @change="updateConversion()"
                            class="h-12 w-full rounded-xl border
                               border-[#D8CEC2]
                               bg-[#FFFDF9]
                               px-4
                               text-sm text-[#2B2118]
                               outline-none
                               focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]">

                            <option value="">
                                {{ __('messages.select_account') }}
                            </option>

                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->name[app()->getLocale()] ?? ($account->name['en'] ?? '') }}

                                    -

                                    {{ number_format($account->balance, 2) }}

                                    {{ $account->currency }}

                                </option>
                            @endforeach

                        </select>


                        <template x-if="selectedAccountData">

                            <p
                                class="mt-1 text-xs
                                   text-[#8A7A68]
                                   dark:text-[#C4B3A1]">

                                {{ __('messages.account_currency') }}:

                                <strong
                                    x-text="
                                    selectedAccountData.currency
                                "></strong>

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

                        <div class="mb-2 flex items-center justify-between gap-3">

                            <label
                                class="block text-sm font-medium
                   text-[#3E342C]
                   dark:text-[#E7DDD2]">
                                {{ __('messages.category') }}
                            </label>


                            <button type="button" id="openCategoryModal"
                                class="inline-flex items-center gap-1
                   text-xs font-medium
                   text-[#6B4A2D]
                   hover:text-[#8A6238]
                   dark:text-[#D8B77A]">
                                <i class="fa-solid fa-plus"></i>

                                {{ __('messages.quick_add_category') }}
                            </button>

                        </div>


                        <select id="categorySelect" name="category_id"
                            class="h-12 w-full rounded-xl border
               border-[#D8CEC2]
               bg-[#FFFDF9]
               px-4
               text-sm text-[#2B2118]
               outline-none
               focus:border-[#B88945]

               dark:border-[#594236]
               dark:bg-[#2A1F18]
               dark:text-[#F7F1E8]">

                            <option value="">
                                {{ __('messages.select_category') }}
                            </option>


                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" data-type="{{ $category->type }}"
                                    @selected(old('category_id') == $category->id)>
                                    {{ $category->name[app()->getLocale()] ?? ($category->name['en'] ?? '') }}
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
                               dark:text-[#E7DDD2]">

                            {{ __('messages.amount') }}

                            <template x-if="transactionCurrency">
                                <span>
                                    (
                                    <span
                                        x-text="
                                        transactionCurrency
                                    "></span>
                                    )
                                </span>
                            </template>

                        </label>


                        <input type="number" name="amount" step="0.01" min="0.01" x-model="transactionAmount"
                            @input.debounce.500ms="
                            updateConversion()
                        "
                            value="{{ old('amount') }}"
                            class="h-12 w-full rounded-xl border
                               border-[#D8CEC2]
                               bg-[#FFFDF9]
                               px-4
                               text-sm text-[#2B2118]
                               outline-none
                               focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]">

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
                               dark:text-[#E7DDD2]">
                            {{ __('messages.transaction_currency') }}
                        </label>


                        <select name="currency" x-model="transactionCurrency"
                            @change="
                            updateConversion()
                        "
                            class="h-12 w-full rounded-xl border
                               border-[#D8CEC2]
                               bg-[#FFFDF9]
                               px-4
                               text-sm text-[#2B2118]
                               outline-none
                               focus:border-[#B88945]

                               dark:border-[#594236]
                               dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]">

                            <option value="">
                                {{ __('messages.select_currency') }}
                            </option>


                            @foreach (['ILS', 'USD', 'EUR', 'JOD', 'GBP'] as $currency)
                                <option value="{{ $currency }}">
                                    {{ $currency }}
                                </option>
                            @endforeach

                        </select>


                        @error('currency')
                            <p class="mt-1 text-sm text-[#B85C5C]">
                                {{ $message }}
                            </p>
                        @enderror


                        {{-- Exchange preview --}}
                        <div class="mt-3">

                            {{-- Loading --}}
                            <div x-show="loadingRate" x-cloak
                                class="rounded-xl
                                   bg-[#F8F3EC]
                                   px-4 py-3
                                   text-sm
                                   text-[#8A6238]

                                   dark:bg-[#30231B]
                                   dark:text-[#D8B77A]">

                                <i
                                    class="fa-solid
                                       fa-spinner
                                       fa-spin
                                       me-2"></i>

                                {{ __('messages.checking_exchange_rate') }}

                            </div>


                            {{-- Converted Amount --}}
                            <div x-show="
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
                                   dark:bg-[#30231B]">

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
                                           dark:text-[#D8B77A]">
                                        <i
                                            class="fa-solid
                                               fa-arrow-right-arrow-left"></i>
                                    </div>


                                    <div>

                                        <p
                                            class="text-sm font-medium
                                               text-[#2B2118]
                                               dark:text-[#F7F1E8]">
                                            {{ __('messages.estimated_account_amount') }}
                                        </p>


                                        <p
                                            class="mt-1
                                               text-lg font-bold
                                               text-[#6B4A2D]
                                               dark:text-[#D8B77A]">

                                            <span
                                                x-text="
                                                convertedAmount
                                                !== null
                                                    ? Number(
                                                        convertedAmount
                                                    ).toFixed(2)
                                                    : ''
                                            "></span>

                                            <span
                                                x-text="
                                                selectedAccountData
                                                    ? selectedAccountData.currency
                                                    : ''
                                            "></span>

                                        </p>


                                        {{-- Exchange Rate --}}
                                        <p
                                            class="mt-2
                                               text-xs
                                               text-[#8A7A68]
                                               dark:text-[#C4B3A1]">

                                            {{ __('messages.current_exchange_rate') }}:

                                            1

                                            <span
                                                x-text="
                                                selectedAccountData
                                                    ? selectedAccountData.currency
                                                    : ''
                                            "></span>

                                            ≈

                                            <strong
                                                x-text="
                                                inverseRate
                                                    ? Number(
                                                        inverseRate
                                                    ).toFixed(4)
                                                    : ''
                                            "></strong>

                                            <span
                                                x-text="
                                                transactionCurrency
                                            "></span>

                                        </p>


                                        <p
                                            class="mt-2
                                               text-xs
                                               text-[#8A7A68]
                                               dark:text-[#C4B3A1]">

                                            <i
                                                class="fa-solid
                                                   fa-circle-info
                                                   me-1"></i>

                                            {{ __('messages.exchange_rate_notice') }}

                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Same Currency --}}
                            <div x-show="
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
                                   text-xs
                                   text-[#4F7A5A]

                                   dark:bg-[#233329]
                                   dark:text-[#7EAA89]">

                                <i
                                    class="fa-solid
                                       fa-circle-check
                                       me-1"></i>

                                {{ __('messages.same_currency_no_conversion') }}

                            </div>


                            {{-- Error --}}
                            <div x-show="conversionError" x-cloak
                                class="rounded-xl
                                   bg-[#F8EAEA]
                                   px-4 py-3
                                   text-sm
                                   text-[#B85C5C]

                                   dark:bg-[#3A2222]
                                   dark:text-[#DF7E7E]">

                                <i
                                    class="fa-solid
                                       fa-triangle-exclamation
                                       me-2"></i>

                                <span
                                    x-text="
                                    conversionError
                                "></span>

                            </div>

                        </div>

                    </div>


                    {{-- Date --}}
                    <div>

                        <label
                            class="mb-2 block text-sm font-medium
                               text-[#3E342C]
                               dark:text-[#E7DDD2]">
                            {{ __('messages.date') }}
                        </label>


                        <div class="relative">

                            <input id="transaction_date" type="date" name="transaction_date"
                                onclick="
                                this.showPicker()
                            "
                                value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                                class="h-12 w-full
                                   rounded-xl border
                                   border-[#D8CEC2]
                                   bg-[#FFFDF9]
                                   px-4 pe-12
                                   text-sm text-[#2B2118]
                                   outline-none
                                   focus:border-[#B88945]

                                   dark:border-[#594236]
                                   dark:bg-[#2A1F18]
                                   dark:text-[#F7F1E8]">


                            <button type="button"
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
                                   dark:hover:text-[#E3C38F]">
                                <i class="fa-regular
                                       fa-calendar-days"></i>
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
                           dark:text-[#E7DDD2]">
                        {{ __('messages.description') }}
                    </label>

                    <textarea name="description" rows="4"
                        class="w-full rounded-xl border
                           border-[#D8CEC2]
                           bg-[#FFFDF9]
                           px-4 py-3
                           text-sm text-[#2B2118]
                           outline-none
                           focus:border-[#B88945]

                           dark:border-[#594236]
                           dark:bg-[#2A1F18]
                           dark:text-[#F7F1E8]">{{ old('description') }}</textarea>


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
                       dark:border-[#463429]">

                    <a href="{{ route('transactions.index') }}"
                        class="rounded-xl border
                           border-[#D8CEC2]
                           px-5 py-2.5
                           text-sm font-medium
                           text-[#6B4A2D]

                           hover:bg-[#F3E8D8]

                           dark:border-[#594236]
                           dark:text-[#D8B77A]
                           dark:hover:bg-[#30231B]">
                        {{ __('messages.cancel') }}
                    </a>


                    <button type="submit"
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
                    ">
                        {{ __('messages.save_transaction') }}
                    </button>

                </div>
                {{-- Quick Add Category Modal --}}
                <div id="categoryModal"
                    class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/40 px-4">

                    <div
                        class="w-full max-w-lg rounded-2xl
               border border-[#E8DDCF]
               bg-[#FFFDF9]
               p-6 shadow-xl

               dark:border-[#463429]
               dark:bg-[#241B16]">

                        <div class="mb-5 flex items-center justify-between">

                            <div>

                                <h3
                                    class="text-lg font-bold
                           text-[#2B2118]
                           dark:text-[#F7F1E8]">
                                    {{ __('messages.quick_add_category') }}
                                </h3>

                                <p
                                    class="mt-1 text-sm
                           text-[#8A7A68]
                           dark:text-[#C4B3A1]">
                                    {{ __('messages.quick_add_category_description') }}
                                </p>

                            </div>


                            <button type="button" id="closeCategoryModal"
                                class="flex h-9 w-9 items-center justify-center
                       rounded-lg
                       text-[#8A7A68]
                       hover:bg-[#F3E8D8]
                       dark:text-[#C4B3A1]
                       dark:hover:bg-[#30231B]">
                                <i class="fa-solid fa-xmark"></i>
                            </button>

                        </div>


                        <div id="categoryModalError"
                            class="mb-4 hidden rounded-xl
                   bg-[#F8EAEA]
                   px-4 py-3
                   text-sm text-[#B85C5C]

                   dark:bg-[#3A2222]
                   dark:text-[#DF7E7E]">
                        </div>


                        <div class="space-y-4">

                            {{-- Arabic Name --}}
                            <div>

                                <label
                                    class="mb-2 block text-sm font-medium
                           text-[#3E342C]
                           dark:text-[#E7DDD2]">
                                    {{ __('messages.category_name_ar') }}
                                </label>

                                <input type="text" id="quickCategoryNameAr"
                                    class="h-12 w-full rounded-xl border
                           border-[#D8CEC2]
                           bg-[#FFFDF9]
                           px-4
                           text-sm text-[#2B2118]

                           outline-none
                           focus:border-[#B88945]

                           dark:border-[#594236]
                           dark:bg-[#2A1F18]
                           dark:text-[#F7F1E8]">

                            </div>


                            {{-- English Name --}}
                            <div>

                                <label
                                    class="mb-2 block text-sm font-medium
                           text-[#3E342C]
                           dark:text-[#E7DDD2]">
                                    {{ __('messages.category_name_en') }}
                                </label>

                                <input type="text" id="quickCategoryNameEn"
                                    class="h-12 w-full rounded-xl border
                           border-[#D8CEC2]
                           bg-[#FFFDF9]
                           px-4
                           text-sm text-[#2B2118]

                           outline-none
                           focus:border-[#B88945]

                           dark:border-[#594236]
                           dark:bg-[#2A1F18]
                           dark:text-[#F7F1E8]">

                            </div>


                            {{-- Type --}}
                            <div>

                                <label
                                    class="mb-2 block text-sm font-medium
                           text-[#3E342C]
                           dark:text-[#E7DDD2]">
                                    {{ __('messages.type') }}
                                </label>

                                <input type="text" id="quickCategoryTypeText" readonly
                                    class="h-12 w-full rounded-xl border
                           border-[#D8CEC2]
                           bg-[#F8F3EC]
                           px-4
                           text-sm text-[#2B2118]

                           dark:border-[#594236]
                           dark:bg-[#30231B]
                           dark:text-[#F7F1E8]">

                                <input type="hidden" id="quickCategoryType">

                            </div>

                        </div>


                        <div
                            class="mt-6 flex justify-end gap-3
                   border-t border-[#EEE2D5]
                   pt-5
                   dark:border-[#463429]">

                            <button type="button" id="cancelCategoryModal"
                                class="rounded-xl border
                       border-[#D8CEC2]
                       px-4 py-2.5
                       text-sm font-medium
                       text-[#6B4A2D]

                       hover:bg-[#F3E8D8]

                       dark:border-[#594236]
                       dark:text-[#D8B77A]">
                                {{ __('messages.cancel') }}
                            </button>


                            <button type="button" id="saveQuickCategory"
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
                ">
                                <span id="saveCategoryText">
                                    {{ __('messages.save_category') }}
                                </span>

                                <i id="saveCategorySpinner" class="fa-solid fa-spinner fa-spin ms-2 hidden"></i>
                            </button>

                        </div>

                    </div>

                </div>
            </form>

        </div>

    </div>


   <script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Transaction Type + Category Filter
    |--------------------------------------------------------------------------
    */

    const typeSelect =
        document.getElementById('transactionType');

    const categorySelect =
        document.getElementById('categorySelect');


    function filterCategories() {

        if (!typeSelect || !categorySelect) {
            return;
        }

        const selectedType =
            typeSelect.value;

        const options =
            Array.from(
                categorySelect.options
            );


        options.forEach(
            (option, index) => {

                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const categoryType =
                    option.dataset.type;

                option.hidden =
                    selectedType !== ''
                    &&
                    categoryType !== selectedType;
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


    if (typeSelect) {

        typeSelect.addEventListener(
            'change',
            filterCategories
        );
    }


    filterCategories();



    /*
    |--------------------------------------------------------------------------
    | Quick Add Category Modal
    |--------------------------------------------------------------------------
    */

    const categoryModal =
        document.getElementById(
            'categoryModal'
        );

    const openCategoryModal =
        document.getElementById(
            'openCategoryModal'
        );

    const closeCategoryModal =
        document.getElementById(
            'closeCategoryModal'
        );

    const cancelCategoryModal =
        document.getElementById(
            'cancelCategoryModal'
        );

    const saveQuickCategory =
        document.getElementById(
            'saveQuickCategory'
        );

    const quickCategoryNameAr =
        document.getElementById(
            'quickCategoryNameAr'
        );

    const quickCategoryNameEn =
        document.getElementById(
            'quickCategoryNameEn'
        );

    const quickCategoryType =
        document.getElementById(
            'quickCategoryType'
        );

    const quickCategoryTypeText =
        document.getElementById(
            'quickCategoryTypeText'
        );

    const categoryModalError =
        document.getElementById(
            'categoryModalError'
        );

    const saveCategorySpinner =
        document.getElementById(
            'saveCategorySpinner'
        );


    /*
    |--------------------------------------------------------------------------
    | Open Modal
    |--------------------------------------------------------------------------
    */

    function openQuickCategoryModal() {

        if (!categoryModal) {
            return;
        }


        const selectedType =
            typeSelect
                ? typeSelect.value
                : '';


        if (!selectedType) {

            alert(
                @json(
                    __('messages.select_transaction_type_first')
                )
            );

            return;
        }


        if (categoryModalError) {

            categoryModalError
                .classList
                .add('hidden');

            categoryModalError.textContent =
                '';
        }


        if (quickCategoryType) {

            quickCategoryType.value =
                selectedType;
        }


        if (quickCategoryTypeText) {

            quickCategoryTypeText.value =
                selectedType === 'income'
                    ? @json(__('messages.income'))
                    : @json(__('messages.expense'));
        }


        categoryModal
            .classList
            .remove('hidden');

        categoryModal
            .classList
            .add('flex');


        setTimeout(
            function () {

                quickCategoryNameAr
                    ?.focus();
            },
            100
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Close Modal
    |--------------------------------------------------------------------------
    */

    function closeQuickCategoryModal() {

        if (!categoryModal) {
            return;
        }


        categoryModal
            .classList
            .add('hidden');

        categoryModal
            .classList
            .remove('flex');


        if (quickCategoryNameAr) {
            quickCategoryNameAr.value = '';
        }

        if (quickCategoryNameEn) {
            quickCategoryNameEn.value = '';
        }


        if (categoryModalError) {

            categoryModalError
                .classList
                .add('hidden');

            categoryModalError.textContent =
                '';
        }
    }



    /*
    |--------------------------------------------------------------------------
    | Modal Events
    |--------------------------------------------------------------------------
    */

    openCategoryModal?.addEventListener(
        'click',
        openQuickCategoryModal
    );


    closeCategoryModal?.addEventListener(
        'click',
        closeQuickCategoryModal
    );


    cancelCategoryModal?.addEventListener(
        'click',
        closeQuickCategoryModal
    );


    categoryModal?.addEventListener(
        'click',
        function (event) {

            if (
                event.target
                === categoryModal
            ) {
                closeQuickCategoryModal();
            }
        }
    );



    /*
    |--------------------------------------------------------------------------
    | Save Quick Category
    |--------------------------------------------------------------------------
    */

    saveQuickCategory?.addEventListener(
        'click',
        async function () {

            if (categoryModalError) {

                categoryModalError
                    .classList
                    .add('hidden');

                categoryModalError.textContent =
                    '';
            }


            const nameAr =
                quickCategoryNameAr
                    ?.value
                    ?.trim();

            const nameEn =
                quickCategoryNameEn
                    ?.value
                    ?.trim();

            const type =
                quickCategoryType
                    ?.value;


            if (
                !nameAr
                ||
                !nameEn
                ||
                !type
            ) {

                if (categoryModalError) {

                    categoryModalError.textContent =
                        @json(
                            __('messages.fill_required_fields')
                        );

                    categoryModalError
                        .classList
                        .remove('hidden');
                }

                return;
            }


            saveQuickCategory.disabled =
                true;

            saveCategorySpinner
                ?.classList
                .remove('hidden');


            try {

                const response =
                    await fetch(
                        @json(
                            route('categories.quick-store')
                        ),
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        ?.getAttribute(
                                            'content'
                                        )
                            },

                            body:
                                JSON.stringify({
                                    name_ar:
                                        nameAr,

                                    name_en:
                                        nameEn,

                                    type:
                                        type,
                                })
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    let message =
                        data.message
                        ||
                        @json(
                            __('messages.category_create_failed')
                        );


                    if (data.errors) {

                        message =
                            Object
                                .values(
                                    data.errors
                                )
                                .flat()
                                .join(' ');
                    }


                    throw new Error(
                        message
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Add new category to select
                |--------------------------------------------------------------------------
                */

                const option =
                    document.createElement(
                        'option'
                    );


                option.value =
                    data.category.id;

                option.dataset.type =
                    data.category.type;

                option.textContent =
                    data.category.name;


                categorySelect
                    .appendChild(
                        option
                    );


                /*
                 * Select it automatically
                 */

                categorySelect.value =
                    data.category.id;


                closeQuickCategoryModal();


            } catch (error) {

                if (categoryModalError) {

                    categoryModalError.textContent =
                        error.message;

                    categoryModalError
                        .classList
                        .remove('hidden');
                }

            } finally {

                saveQuickCategory.disabled =
                    false;

                saveCategorySpinner
                    ?.classList
                    .add('hidden');
            }
        }
    );

});
</script>
@endsection
