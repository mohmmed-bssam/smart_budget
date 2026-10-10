@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ __('messages.add_transaction') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('messages.add_transaction_description') }}
        </p>
    </div>


    <div
        class="rounded-2xl border border-gray-200 bg-white p-6
               dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <form
            method="POST"
            action="{{ route('transactions.store') }}"
            class="space-y-6"
        >

            @csrf


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Title --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.title') }}
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:text-white/90"
                    >

                    @error('title')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Type --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.type') }}
                    </label>

                    <select
                        id="transactionType"
                        name="type"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >
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
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Account --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.account') }}
                    </label>

                    <select
                        name="account_id"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >

                        <option value="">
                            {{ __('messages.select_account') }}
                        </option>

                        @foreach($accounts as $account)

                            <option
                                value="{{ $account->id }}"
                                @selected(old('account_id') == $account->id)
                            >
                                {{ $account->name[app()->getLocale()]
                                    ?? $account->name['en']
                                    ?? '' }}

                                ({{ $account->currency }})
                            </option>

                        @endforeach

                    </select>

                    @error('account_id')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Category --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.category') }}
                    </label>

                    <select
                        id="categorySelect"
                        name="category_id"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >

                        <option value="">
                            {{ __('messages.select_category') }}
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                data-type="{{ $category->type }}"
                                @selected(old('category_id') == $category->id)
                            >
                                {{ $category->name[app()->getLocale()]
                                    ?? $category->name['en']
                                    ?? '' }}
                            </option>

                        @endforeach

                    </select>

                    @error('category_id')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Amount --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.amount') }}
                    </label>

                    <input
                        type="number"
                        name="amount"
                        step="0.01"
                        min="0.01"
                        value="{{ old('amount') }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:text-white/90"
                    >

                    @error('amount')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Date --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.date') }}
                    </label>

                    <input
                        type="date"
                        name="transaction_date"
                        value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:text-white/90"
                    >

                    @error('transaction_date')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>


            {{-- Description --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('messages.description') }}
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300
                           bg-transparent px-4 py-3 text-sm text-gray-800
                           outline-none focus:border-brand-500
                           dark:border-gray-700 dark:text-white/90"
                >{{ old('description') }}</textarea>
            </div>


            {{-- Actions --}}
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">

                <a
                    href="{{ route('transactions.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5
                           text-sm font-medium text-gray-700
                           dark:border-gray-700 dark:text-gray-300"
                >
                    {{ __('messages.cancel') }}
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5
                           text-sm font-medium text-white hover:bg-brand-600"
                >
                    {{ __('messages.save_transaction') }}
                </button>

            </div>

        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const typeSelect = document.getElementById('transactionType');
    const categorySelect = document.getElementById('categorySelect');

    if (!typeSelect || !categorySelect) return;

    const options = Array.from(categorySelect.options);

    function filterCategories() {

        const selectedType = typeSelect.value;

        options.forEach((option, index) => {

            if (index === 0) {
                option.hidden = false;
                return;
            }

            const categoryType = option.dataset.type;

            option.hidden =
                selectedType !== '' &&
                categoryType !== selectedType;

        });

        const selectedOption =
            categorySelect.options[categorySelect.selectedIndex];

        if (
            selectedOption &&
            selectedOption.dataset.type &&
            selectedOption.dataset.type !== selectedType
        ) {
            categorySelect.value = '';
        }
    }

    typeSelect.addEventListener('change', filterCategories);

    filterCategories();
});
</script>

@endsection
