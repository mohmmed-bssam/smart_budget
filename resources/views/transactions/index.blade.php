@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ __('messages.transactions') }}
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.transactions_description') }}
            </p>
        </div>

        <a
            href="{{ route('transactions.create') }}"
            class="inline-flex items-center justify-center rounded-lg
                   bg-brand-500 px-4 py-2.5 text-sm font-medium
                   text-white hover:bg-brand-600"
        >
            {{ __('messages.add_transaction') }}
        </a>

    </div>


    {{-- Table --}}
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white
               dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <div class="max-w-full overflow-x-auto custom-scrollbar">

            <table class="min-w-full">

                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.transaction') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.account') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.category') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.type') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.amount') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.date') }}
                            </span>
                        </th>

                        <th class="px-5 py-3 text-start">
                            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.actions') }}
                            </span>
                        </th>

                    </tr>
                </thead>


                <tbody>

                    @forelse($transactions as $transaction)

                        <tr class="border-b border-gray-100 dark:border-gray-800">

                            <td class="px-5 py-4">

                                <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $transaction->title }}
                                </p>

                                @if($transaction->description)
                                    <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($transaction->description, 40) }}
                                    </span>
                                @endif

                            </td>


                            <td class="px-5 py-4">

                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">
                                    {{ $transaction->account?->name[app()->getLocale()]
                                        ?? $transaction->account?->name['en']
                                        ?? '-' }}
                                </span>

                            </td>


                            <td class="px-5 py-4">

                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">
                                    {{ $transaction->category?->name[app()->getLocale()]
                                        ?? $transaction->category?->name['en']
                                        ?? '-' }}
                                </span>

                            </td>


                            <td class="px-5 py-4">

                                @if($transaction->type === 'income')

                                    <span
                                        class="rounded-full bg-success-50 px-2.5 py-1
                                               text-theme-xs font-medium text-success-600
                                               dark:bg-success-500/15 dark:text-success-500"
                                    >
                                        {{ __('messages.income') }}
                                    </span>

                                @else

                                    <span
                                        class="rounded-full bg-error-50 px-2.5 py-1
                                               text-theme-xs font-medium text-error-600
                                               dark:bg-error-500/15 dark:text-error-500"
                                    >
                                        {{ __('messages.expense') }}
                                    </span>

                                @endif

                            </td>


                            <td class="px-5 py-4">

                                <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">

                                    {{ number_format($transaction->amount, 2) }}

                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $transaction->account?->currency }}
                                    </span>

                                </span>

                            </td>


                            <td class="px-5 py-4">

                                <span class="text-theme-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->transaction_date?->format('Y-m-d') }}
                                </span>

                            </td>


                            <td class="px-5 py-4">

                                <div class="flex flex-wrap gap-2">

                                    <a
                                        href="{{ route('transactions.show', $transaction) }}"
                                        class="rounded-lg border border-gray-300 px-3 py-2
                                               text-theme-xs font-medium text-gray-700
                                               dark:border-gray-700 dark:text-gray-300"
                                    >
                                        {{ __('messages.view') }}
                                    </a>


                                    <a
                                        href="{{ route('transactions.edit', $transaction) }}"
                                        class="rounded-lg border border-gray-300 px-3 py-2
                                               text-theme-xs font-medium text-gray-700
                                               dark:border-gray-700 dark:text-gray-300"
                                    >
                                        {{ __('messages.edit') }}
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('transactions.destroy', $transaction) }}"
                                        onsubmit="return confirm(@js(__('messages.delete_transaction_confirmation')))"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-error-300 px-3 py-2
                                                   text-theme-xs font-medium text-error-600
                                                   dark:border-error-800 dark:text-error-500"
                                        >
                                            {{ __('messages.delete') }}
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('messages.no_transactions_found') }}
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Pagination --}}
    @if($transactions->hasPages())
        <div>
            {{ $transactions->links() }}
        </div>
    @endif

</div>

@endsection
