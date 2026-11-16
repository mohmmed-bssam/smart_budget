@extends('layouts.app')
@section('title', __('messages.edit_account_title'))
@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ __('messages.edit_account') }}
        </h2>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6
                dark:border-gray-800 dark:bg-white/[0.03]">

        <form method="POST" action="{{ route('accounts.update', $account) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.account_name_ar') }}
                    </label>

                    <input
                        type="text"
                        name="name_ar"
                        value="{{ old('name_ar', $account->name['ar'] ?? '') }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 dark:border-gray-700 dark:text-white/90"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.account_name_en') }}
                    </label>

                    <input
                        type="text"
                        name="name_en"
                        value="{{ old('name_en', $account->name['en'] ?? '') }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 dark:border-gray-700 dark:text-white/90"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.account_type') }}
                    </label>

                    <select
                        name="type"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >
                        @foreach([
                            'cash' => __('messages.account_type_cash'),
                            'bank' => __('messages.account_type_bank'),
                            'wallet' => __('messages.account_type_wallet'),
                            'savings' => __('messages.account_type_savings'),
                        ] as $value => $label)

                            <option value="{{ $value }}" @selected(old('type', $account->type) === $value)>
                                {{ $label }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.current_balance') }}
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="balance"
                        value="{{ old('balance', $account->balance) }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 dark:border-gray-700 dark:text-white/90"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.currency') }}
                    </label>

                    <select
                        name="currency"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >
                        @foreach(['USD','EUR','ILS','JOD','GBP'] as $currency)
                            <option value="{{ $currency }}" @selected(old('currency', $account->currency) === $currency)>
                                {{ $currency }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
                <a href="{{ route('accounts.index') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700
                          dark:border-gray-700 dark:text-gray-300">
                    {{ __('messages.cancel') }}
                </a>

                <button type="submit"
                        class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white">
                    {{ __('messages.save_changes') }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
