@props([
    'recentTransactions' => [],
])

@php
    $getTypeClasses = function ($type) {
        $baseClasses = 'rounded-full px-2.5 py-1 text-theme-xs font-medium';

        return match ($type) {
            'income' =>
                $baseClasses
                . ' bg-[#EEF4EF] text-[#4F7A5A] dark:bg-[#233329] dark:text-[#7EAA89]',

            'expense' =>
                $baseClasses
                . ' bg-[#F8EAEA] text-[#B85C5C] dark:bg-[#3A2222] dark:text-[#DF7E7E]',

            default =>
                $baseClasses
                . ' bg-[#F3E8D8] text-[#8A7A68] dark:bg-[#30231B] dark:text-[#C4B3A1]',
        };
    };
@endphp

<div
    class="overflow-hidden rounded-2xl border
           border-[#E8DDCF] bg-[#FFFDF9]
           px-4 pb-3 pt-4
           dark:border-[#463429] dark:bg-[#241B16]
           sm:px-6"
>

    {{-- Header --}}
    <div
        class="mb-4 flex flex-col gap-2
               sm:flex-row sm:items-center sm:justify-between"
    >

        <div>

            <h3
                class="text-lg font-semibold
                       text-[#2B2118]
                       dark:text-[#F7F1E8]"
            >
                {{ __('messages.recent_transactions_this_month') }}
            </h3>

            <p
                class="mt-1 text-sm
                       text-[#8A7A68]
                       dark:text-[#C4B3A1]"
            >
                {{ __('messages.recent_transactions_this_month_description') }}
            </p>

        </div>


        <a
            href="{{ route('admin.reports.index') }}"

            class="inline-flex items-center justify-center
                   rounded-lg border
                   px-4 py-2.5
                   text-theme-sm font-medium
                   transition

                   border-[#D8CEC2]
                   bg-[#FFFDF9]
                   text-[#6B4A2D]

                   hover:bg-[#F3E8D8]
                   hover:text-[#3E2A1F]

                   dark:border-[#594236]
                   dark:bg-[#241B16]
                   dark:text-[#D8B77A]

                   dark:hover:bg-[#30231B]
                   dark:hover:text-[#F7F1E8]"
        >
            {{ __('messages.view_all') }}
        </a>

    </div>


    {{-- Table --}}
    <div class="max-w-full overflow-x-auto custom-scrollbar">

        <table class="min-w-full">

            <thead>

                <tr
                    class="border-t
                           border-[#EEE2D5]
                           dark:border-[#463429]"
                >

                    <th class="py-3 text-start">
                        <p
                            class="font-medium
                                   text-[#8A7A68]
                                   text-theme-xs
                                   dark:text-[#C4B3A1]"
                        >
                            {{ __('messages.user') }}
                        </p>
                    </th>


                    <th class="py-3 text-start">
                        <p
                            class="font-medium
                                   text-[#8A7A68]
                                   text-theme-xs
                                   dark:text-[#C4B3A1]"
                        >
                            {{ __('messages.transaction') }}
                        </p>
                    </th>


                    <th class="py-3 text-start">
                        <p
                            class="font-medium
                                   text-[#8A7A68]
                                   text-theme-xs
                                   dark:text-[#C4B3A1]"
                        >
                            {{ __('messages.type') }}
                        </p>
                    </th>


                    <th class="py-3 text-start">
                        <p
                            class="font-medium
                                   text-[#8A7A68]
                                   text-theme-xs
                                   dark:text-[#C4B3A1]"
                        >
                            {{ __('messages.amount') }}
                        </p>
                    </th>


                    <th class="py-3 text-start">
                        <p
                            class="font-medium
                                   text-[#8A7A68]
                                   text-theme-xs
                                   dark:text-[#C4B3A1]"
                        >
                            {{ __('messages.date') }}
                        </p>
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($recentTransactions as $transaction)

                    <tr
                        class="border-t
                               border-[#EEE2D5]
                               transition

                               hover:bg-[#FBF7F1]

                               dark:border-[#463429]
                               dark:hover:bg-[#2A1F18]"
                    >

                        {{-- User --}}
                        <td class="py-3 whitespace-nowrap">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-9 w-9
                                           items-center justify-center
                                           rounded-full
                                           text-sm font-semibold

                                           bg-[#F3E8D8]
                                           text-[#6B4A2D]

                                           dark:bg-[#30231B]
                                           dark:text-[#D8B77A]"
                                >

                                    {{ strtoupper(
                                        substr(
                                            $transaction->user?->name ?? 'U',
                                            0,
                                            1
                                        )
                                    ) }}

                                </div>


                                <div>

                                    <p
                                        class="font-medium
                                               text-[#2B2118]
                                               text-theme-sm
                                               dark:text-[#F7F1E8]"
                                    >
                                        {{ $transaction->user?->name
                                            ?? __('messages.unknown_user') }}
                                    </p>


                                    <span
                                        class="text-[#8A7A68]
                                               text-theme-xs
                                               dark:text-[#C4B3A1]"
                                    >
                                        {{ $transaction->user?->email ?? '-' }}
                                    </span>

                                </div>

                            </div>

                        </td>


                        {{-- Transaction --}}
                        <td class="py-3 whitespace-nowrap">

                            <p
                                class="text-[#5C4D40]
                                       text-theme-sm
                                       dark:text-[#E7DDD2]"
                            >
                                {{ $transaction->title ?? '-' }}
                            </p>

                        </td>


                        {{-- Type --}}
                        <td class="py-3 whitespace-nowrap">

                            <span
                                class="{{ $getTypeClasses($transaction->type) }}"
                            >

                                {{ $transaction->type === 'income'
                                    ? __('messages.income')
                                    : __('messages.expense') }}

                            </span>

                        </td>


                        {{-- Amount --}}
                        <td class="py-3 whitespace-nowrap">

                            <p
                                class="font-semibold
                                       text-theme-sm

                                       {{ $transaction->type === 'income'
                                            ? 'text-[#4F7A5A] dark:text-[#7EAA89]'
                                            : 'text-[#B85C5C] dark:text-[#DF7E7E]' }}"
                            >

                                {{ $transaction->type === 'income'
                                    ? '+'
                                    : '-' }}

                                {{ number_format(
                                    $transaction->amount,
                                    2
                                ) }}

                                {{ $transaction->currency }}

                            </p>

                        </td>


                        {{-- Date --}}
                        <td class="py-3 whitespace-nowrap">

                            <p
                                class="text-[#8A7A68]
                                       text-theme-sm
                                       dark:text-[#C4B3A1]"
                            >

                                {{ optional(
                                    $transaction->transaction_date
                                )->format('Y-m-d') }}

                            </p>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td colspan="5" class="py-10 text-center">

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
                                <i class="fa-solid fa-receipt"></i>
                            </div>

                            <p
                                class="text-sm
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >
                                {{ __('messages.no_transactions_this_month') }}
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
