@props([
    'monthlyIncome' => [],
    'monthlyExpenses' => [],
    'year' => null,
])
<div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6
           dark:border-gray-800 dark:bg-white/[0.03]">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('messages.income_expenses') }}
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.income_expenses_description') }}
                {{ $year }}
            </p>
        </div>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">

        <div id="chartOne" data-income='@json($monthlyIncome)' data-expenses='@json($monthlyExpenses)'
            data-income-label="{{ __('messages.income') }}" data-expenses-label="{{ __('messages.expense') }}"
            class="-ml-5 h-full min-w-[690px] pl-2 xl:min-w-full">
        </div>
    </div>

</div>
