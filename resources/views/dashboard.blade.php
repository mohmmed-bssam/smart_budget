@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Welcome --}}
    <div>
        <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
            {{ __('messages.welcome_back') }},
            {{ auth()->user()->name }}
        </h2>

        <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
            {{ __('messages.user_dashboard_description') }}
        </p>
    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

        {{-- Accounts --}}
        <div
            class="rounded-2xl border p-5 transition
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl
                       bg-[#F3E8D8] text-[#6B4A2D]
                       dark:bg-[#30231B] dark:text-[#D8B77A]"
            >

                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9Z"/>
                </svg>

            </div>

            <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.total_accounts') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($stats['accounts'] ?? 0) }}
            </h3>

        </div>


        {{-- Transactions --}}
        <div
            class="rounded-2xl border p-5 transition
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl
                       bg-[#F3E8D8] text-[#6B4A2D]
                       dark:bg-[#30231B] dark:text-[#D8B77A]"
            >

                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path d="M7 7h9.6l-1.8-1.8 1.1-1.1L19.6 7.8 15.9 11.5l-1.1-1.1 1.8-1.9H7V7Zm10 10H7.4l1.8 1.8-1.1 1.1-3.7-3.7 3.7-3.7 1.1 1.1-1.8 1.9H17V17Z"/>
                </svg>

            </div>

            <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.total_transactions') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($stats['transactions'] ?? 0) }}
            </h3>

        </div>


        {{-- Goals --}}
        <div
            class="rounded-2xl border p-5 transition
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl
                       bg-[#F3E8D8] text-[#6B4A2D]
                       dark:bg-[#30231B] dark:text-[#D8B77A]"
            >

                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path d="M12 3a9 9 0 1 0 9 9h-2a7 7 0 1 1-7-7V3Zm1 0v9h9A9 9 0 0 0 13 3Z"/>
                </svg>

            </div>

            <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.total_goals') }}
            </p>

            <h3 class="mt-2 text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($stats['goals'] ?? 0) }}
            </h3>

        </div>


        {{-- Income --}}
        <div
            class="rounded-2xl border p-5 transition
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl
                       bg-[#EEF4EF] text-[#4F7A5A]
                       dark:bg-[#233329] dark:text-[#7EAA89]"
            >
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>

            <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.income_this_month') }}
            </p>

            <h3 class="mt-3 text-2xl font-bold text-[#4F7A5A] dark:text-[#7EAA89]">

                {{ number_format(
                    $stats['income_this_month'] ?? 0,
                    2
                ) }}

                <span class="text-base font-semibold">
                    {{ $baseCurrency }}
                </span>

            </h3>

        </div>


        {{-- Expenses --}}
        <div
            class="rounded-2xl border p-5 transition
                   border-[#E8DDCF] bg-[#FFFDF9]
                   dark:border-[#463429] dark:bg-[#241B16]"
        >

            <div
                class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl
                       bg-[#F8EAEA] text-[#B85C5C]
                       dark:bg-[#3A2222] dark:text-[#DF7E7E]"
            >
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>

            <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.expenses_this_month') }}
            </p>

            <h3 class="mt-3 text-2xl font-bold text-[#B85C5C] dark:text-[#DF7E7E]">

                {{ number_format(
                    $stats['expenses_this_month'] ?? 0,
                    2
                ) }}

                <span class="text-base font-semibold">
                    {{ $baseCurrency }}
                </span>

            </h3>

        </div>

    </div>



    {{-- Recent Transactions --}}
    <div
        class="overflow-hidden rounded-2xl border
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div
            class="flex flex-col gap-3 border-b px-5 py-4
                   border-[#EEE2D5]
                   sm:flex-row sm:items-center sm:justify-between
                   dark:border-[#463429]"
        >

            <div>

                <h3 class="text-lg font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ __('messages.recent_transactions') }}
                </h3>

                <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.your_latest_transactions') }}
                </p>

            </div>


            <a
                href="{{ route('transactions.index') }}"

                class="inline-flex items-center justify-center rounded-lg
                       border px-4 py-2.5 text-sm font-medium transition

                       border-[#D8CEC2]
                       text-[#6B4A2D]
                       hover:bg-[#F3E8D8]

                       dark:border-[#594236]
                       dark:text-[#D8B77A]
                       dark:hover:bg-[#30231B]"
            >
                {{ __('messages.view_all') }}
            </a>

        </div>


        <div class="max-w-full overflow-x-auto custom-scrollbar">

            <table class="min-w-full">

                <thead>

                    <tr class="border-b border-[#EEE2D5] dark:border-[#463429]">

                        <th class="px-5 py-3 text-start">

                            <span class="text-theme-xs font-medium text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ __('messages.transaction') }}
                            </span>

                        </th>


                        <th class="px-5 py-3 text-start">

                            <span class="text-theme-xs font-medium text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ __('messages.type') }}
                            </span>

                        </th>


                        <th class="px-5 py-3 text-start">

                            <span class="text-theme-xs font-medium text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ __('messages.amount') }}
                            </span>

                        </th>


                        <th class="px-5 py-3 text-start">

                            <span class="text-theme-xs font-medium text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ __('messages.date') }}
                            </span>

                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($recentTransactions as $transaction)

                        <tr
                            class="border-b border-[#EEE2D5]
                                   transition hover:bg-[#FBF7F1]
                                   dark:border-[#463429]
                                   dark:hover:bg-[#2A1F18]"
                        >

                            {{-- Transaction --}}
                            <td class="px-5 py-4">

                                <p class="text-theme-sm font-medium text-[#2B2118] dark:text-[#F7F1E8]">
                                    {{ $transaction->title ?? '-' }}
                                </p>


                                @if($transaction->description)

                                    <span class="text-theme-xs text-[#8A7A68] dark:text-[#C4B3A1]">

                                        {{ Str::limit(
                                            $transaction->description,
                                            40
                                        ) }}

                                    </span>

                                @endif

                            </td>


                            {{-- Type --}}
                            <td class="px-5 py-4">

                                @if($transaction->type === 'income')

                                    <span
                                        class="rounded-full px-2.5 py-1
                                               text-theme-xs font-medium"

                                        style="
                                            background-color:
                                                rgba(79, 122, 90, .12);

                                            color:
                                                #4F7A5A;
                                        "
                                    >

                                        {{ __('messages.income') }}

                                    </span>

                                @else

                                    <span
                                        class="rounded-full px-2.5 py-1
                                               text-theme-xs font-medium"

                                        style="
                                            background-color:
                                                rgba(184, 92, 92, .12);

                                            color:
                                                #B85C5C;
                                        "
                                    >

                                        {{ __('messages.expense') }}

                                    </span>

                                @endif

                            </td>


                            {{-- Amount --}}
                            <td class="px-5 py-4">

                                <div>

                                    {{-- Original transaction amount --}}
                                    <p
                                        class="text-theme-sm font-semibold
                                               text-[#2B2118]
                                               dark:text-[#F7F1E8]"
                                    >

                                        {{ number_format(
                                            $transaction->amount,
                                            2
                                        ) }}

                                        {{ $transaction->currency }}

                                    </p>


                                    {{-- Actual Account Amount --}}
                                    @if(
                                        $transaction->account_amount
                                        &&
                                        $transaction->account_currency
                                        &&
                                        $transaction->currency
                                            !==
                                        $transaction->account_currency
                                    )

                                        <p
                                            class="mt-1 text-xs
                                                   text-[#8A7A68]
                                                   dark:text-[#C4B3A1]"
                                        >

                                            {{ __('messages.account_amount') }}:

                                            {{ number_format(
                                                $transaction->account_amount,
                                                2
                                            ) }}

                                            {{ $transaction->account_currency }}

                                        </p>

                                    @endif

                                </div>

                            </td>


                            {{-- Date --}}
                            <td class="px-5 py-4">

                                <span
                                    class="text-theme-sm
                                           text-[#8A7A68]
                                           dark:text-[#C4B3A1]"
                                >

                                    {{ $transaction
                                        ->transaction_date
                                        ?->format('Y-m-d') }}

                                </span>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-5 py-12 text-center"
                            >

                                <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">

                                    {{ __('messages.no_transactions_found') }}

                                </p>


                                <a
                                    href="{{ route('transactions.create') }}"

                                    class="mt-3 inline-flex rounded-lg
                                           px-4 py-2
                                           text-sm font-medium
                                           text-white transition"

                                    style="
                                        background:
                                            linear-gradient(
                                                135deg,
                                                #5E4029,
                                                #8A6238
                                            );
                                    "
                                >

                                    {{ __('messages.add_first_transaction') }}

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
