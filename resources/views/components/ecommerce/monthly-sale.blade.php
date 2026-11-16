@props([
    'monthlyIncome' => [],
    'monthlyExpenses' => [],
    'year' => null,
    'baseCurrency' => 'USD',
])

<div
    class="overflow-hidden rounded-2xl border
           border-[#E8DDCF] bg-[#FFFDF9]
           px-5 pt-5 sm:px-6 sm:pt-6
           dark:border-[#463429] dark:bg-[#241B16]"
>

    <div class="flex items-center justify-between">

        <div>

            <h3 class="text-lg font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ __('messages.income_expenses') }}
                ({{ $baseCurrency }})
            </h3>

            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.income_expenses_description') }}
                {{ $year }}
            </p>

        </div>

    </div>


    <div class="mt-4 max-w-full overflow-x-auto custom-scrollbar">

        <div
            id="chartOne"

            data-income='@json($monthlyIncome)'
            data-expenses='@json($monthlyExpenses)'

            data-income-label="{{ __('messages.income') }}"
            data-expenses-label="{{ __('messages.expense') }}"

            data-currency="{{ $baseCurrency }}"

            class="-ml-5 h-full min-w-[690px] pl-2 xl:min-w-full"
        >
        </div>

    </div>

</div>
