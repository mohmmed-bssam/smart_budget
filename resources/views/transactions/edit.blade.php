@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ __('messages.edit_transaction') }}
        </h2>
    </div>


    <div
        class="rounded-2xl border border-gray-200 bg-white p-6
               dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <form
            method="POST"
            action="{{ route('transactions.update', $transaction) }}"
            class="space-y-6"
        >

            @csrf
            @method('PUT')


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.title') }}
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $transaction->title) }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:text-white/90"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.type') }}
                    </label>

                    <select
                        id="transactionType"
                        name="type"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >

                        <option
                            value="income"
                            @selected(old('type', $transaction->type) === 'income')
                        >
                            {{ __('messages.income') }}
                        </option>

                        <option
                            value="expense"
                            @selected(old('type', $transaction->type) === 'expense')
                        >
                            {{ __('messages.expense') }}
                        </option>

                    </select>
                </div>


                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.account') }}
                    </label>

                    <select
                        name="account_id"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >

                        @foreach($accounts as $account)

                            <option
                                value="{{ $account->id }}"
                                @selected(old('account_id', $transaction->account_id) == $account->id)
                            >
                                {{ $account->name[app()->getLocale()]
                                    ?? $account->name['en']
                                    ?? '' }}

                                ({{ $account->currency }})
                            </option>

                        @endforeach

                    </select>
                </div>


                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.category') }}
                    </label>

                    <select
                        id="categorySelect"
                        name="category_id"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                data-type="{{ $category->type }}"
                                @selected(old('category_id', $transaction->category_id) == $category->id)
                            >
                                {{ $category->name[app()->getLocale()]
                                    ?? $category->name['en']
                                    ?? '' }}
                            </option>

                        @endforeach

                    </select>
                </div>


                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.amount') }}
                    </label>

                    <input
                        type="number"
                        name="amount"
                        step="0.01"
                        min="0.01"
                        value="{{ old('amount', $transaction->amount) }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:text-white/90"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.date') }}
                    </label>

                    <input
                        type="date"
                        name="transaction_date"
                        value="{{ old(
                            'transaction_date',
                            $transaction->transaction_date?->format('Y-m-d')
                        ) }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:text-white/90"
                    >
                </div>

            </div>


            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('messages.description') }}
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300
                           bg-transparent px-4 py-3 text-sm text-gray-800
                           dark:border-gray-700 dark:text-white/90"
                >{{ old('description', $transaction->description) }}</textarea>
            </div>


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
                           text-sm font-medium text-white"
                >
                    {{ __('messages.save_changes') }}
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

        options.forEach((option) => {

            const categoryType = option.dataset.type;

            if (!categoryType) return;

            option.hidden = categoryType !== selectedType;

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
