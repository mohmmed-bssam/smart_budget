@extends('layouts.app')
@section('title', __('messages.budgets'))

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ __('messages.budgets') }}
                </h2>

                <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.budgets_description') }}
                </p>
            </div>

            <a href="{{ route('budgets.create') }}"
                class="inline-flex items-center justify-center rounded-xl
                   px-4 py-2.5 text-sm font-medium text-white transition"
                style="background: linear-gradient(135deg, #5E4029, #8A6238);">
                <i class="fa-solid fa-plus me-2"></i>

                {{ __('messages.add_budget') }}
            </a>

        </div>


        {{-- Budgets --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

            @forelse($budgets as $budget)
                @php
                    $percentage = $budget->percentage ?? 0;

                    $progressWidth = min($percentage, 100);

                    $isOverBudget = ($budget->remaining ?? 0) < 0;
                @endphp


                <div
                    class="rounded-2xl border p-5
                       border-[#E8DDCF] bg-[#FFFDF9]
                       dark:border-[#463429] dark:bg-[#241B16]">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl
                                   bg-[#F3E8D8] text-[#6B4A2D]
                                   dark:bg-[#30231B] dark:text-[#D8B77A]">

                                @if ($budget->category?->icon)
                                    <i class="{{ $budget->category->icon }}"></i>
                                @else
                                    <i class="fa-solid fa-wallet"></i>
                                @endif

                            </div>


                            <div>

                                <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">

                                    {{ $budget->category?->name[app()->getLocale()] ?? ($budget->category?->name['en'] ?? '-') }}

                                </h3>


                                <p class="mt-1 text-xs text-[#8A7A68] dark:text-[#C4B3A1]">

                                    {{ __('messages.month_' . $budget->month) }}

                                    ·

                                    {{ $budget->year }}

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Budget Amount --}}
                    <div class="mt-6">

                        <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                            {{ __('messages.budget_amount') }}
                        </p>

                        <p class="mt-1 text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">

                            {{ number_format($budget->amount, 2) }}

                            <span class="text-base font-medium text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ $budget->currency }}
                            </span>

                        </p>

                    </div>


                    {{-- Base value --}}
                    @if ($budget->currency !== $budget->base_currency)
                        <div class="mt-2 text-xs text-[#8A7A68] dark:text-[#C4B3A1]">

                            {{ __('messages.equivalent_to') }}

                            {{ number_format($budget->base_amount, 2) }}

                            {{ $budget->base_currency }}

                        </div>
                    @endif


                    {{-- Progress --}}
                    <div class="mt-5">

                        <div class="mb-2 flex items-center justify-between">

                            <span class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ __('messages.budget_progress') }}
                            </span>

                            <span
                                class="text-sm font-semibold
                                {{ $isOverBudget ? 'text-[#B85C5C] dark:text-[#DF7E7E]' : 'text-[#6B4A2D] dark:text-[#D8B77A]' }}">

                                {{ number_format($percentage, 1) }}%

                            </span>

                        </div>


                        <div
                            class="h-2.5 w-full overflow-hidden rounded-full
                               bg-[#F3E8D8] dark:bg-[#30231B]">

                            <div class="h-full rounded-full transition-all duration-300"
                                style="
                                width: {{ $progressWidth }}%;
                                background-color:
                                    {{ $isOverBudget ? '#B85C5C' : '#B88945' }};
                            ">
                            </div>

                        </div>

                    </div>


                    {{-- Details --}}
                    <div class="mt-5 grid grid-cols-2 gap-3">

                        {{-- Spent --}}
                        <div
                            class="rounded-xl p-3
                               bg-[#F8F3EC]
                               dark:bg-[#2A1F18]">

                            <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ __('messages.spent') }}
                            </p>

                            <p class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">

                                {{ number_format($budget->spent ?? 0, 2) }}

                                <span class="text-xs font-medium text-[#8A7A68] dark:text-[#C4B3A1]">
                                    {{ $budget->base_currency }}
                                </span>

                            </p>

                        </div>


                        {{-- Remaining / Exceeded --}}
                        <div
                            class="rounded-xl p-3
           {{ $isOverBudget ? 'bg-[#F8EAEA] dark:bg-[#3A2222]' : 'bg-[#EEF4EF] dark:bg-[#233329]' }}">

                            <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">

                                @if ($isOverBudget)
                                    {{ __('messages.exceeded_by') }}
                                @else
                                    {{ __('messages.remaining') }}
                                @endif

                            </p>

                            <p
                                class="mt-1 font-semibold
               {{ $isOverBudget ? 'text-[#B85C5C] dark:text-[#DF7E7E]' : 'text-[#4F7A5A] dark:text-[#7EAA89]' }}">

                                @if ($isOverBudget)
                                    {{ number_format(abs($budget->remaining ?? 0), 2) }}
                                @else
                                    {{ number_format($budget->remaining ?? 0, 2) }}
                                @endif

                                <span class="text-xs font-medium">
                                    {{ $budget->base_currency }}
                                </span>

                            </p>

                        </div>

                    </div>


                    {{-- Exceeded --}}
                    @if ($isOverBudget)
                        <div
                            class="mt-4 rounded-xl px-3 py-2 text-sm
                               bg-[#F8EAEA] text-[#B85C5C]
                               dark:bg-[#3A2222] dark:text-[#DF7E7E]">

                            <i class="fa-solid fa-triangle-exclamation me-1"></i>

                            {{ __('messages.budget_exceeded') }}

                        </div>
                    @endif


                    {{-- Actions --}}
                    <div
                        class="mt-5 flex flex-wrap gap-2 border-t pt-4
                           border-[#EEE2D5] dark:border-[#463429]">

                        <a href="{{ route('budgets.show', $budget) }}"
                            class="rounded-lg border px-3 py-2 text-xs font-medium
                               border-[#D8CEC2] text-[#6B4A2D]
                               hover:bg-[#F3E8D8]
                               dark:border-[#594236] dark:text-[#D8B77A]
                               dark:hover:bg-[#30231B]">
                            {{ __('messages.view') }}
                        </a>


                        <a href="{{ route('budgets.edit', $budget) }}"
                            class="rounded-lg border px-3 py-2 text-xs font-medium
                               border-[#D8CEC2] text-[#6B4A2D]
                               hover:bg-[#F3E8D8]
                               dark:border-[#594236] dark:text-[#D8B77A]
                               dark:hover:bg-[#30231B]">
                            {{ __('messages.edit') }}
                        </a>


                        <form method="POST" action="{{ route('budgets.destroy', $budget) }}"
                            onsubmit="return confirm(@js(__('messages.delete_budget_confirmation')))">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="rounded-lg border px-3 py-2 text-xs font-medium
                                   border-[#E6BDBD] text-[#B85C5C]
                                   hover:bg-[#F8EAEA]
                                   dark:border-[#674040] dark:text-[#DF7E7E]
                                   dark:hover:bg-[#3A2222]">
                                {{ __('messages.delete') }}
                            </button>

                        </form>

                    </div>

                </div>


            @empty

                <div
                    class="col-span-full rounded-2xl border p-10 text-center
                       border-[#E8DDCF] bg-[#FFFDF9]
                       dark:border-[#463429] dark:bg-[#241B16]">

                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl
                           bg-[#F3E8D8] text-[#6B4A2D]
                           dark:bg-[#30231B] dark:text-[#D8B77A]">

                        <i class="fa-solid fa-wallet text-xl"></i>

                    </div>


                    <h3 class="mt-4 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">

                        {{ __('messages.no_budgets_found') }}

                    </h3>


                    <p class="mt-2 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">

                        {{ __('messages.no_budgets_description') }}

                    </p>


                    <a href="{{ route('budgets.create') }}"
                        class="mt-5 inline-flex rounded-xl px-4 py-2.5
                           text-sm font-medium text-white"
                        style="background: linear-gradient(135deg, #5E4029, #8A6238);">

                        {{ __('messages.add_first_budget') }}

                    </a>

                </div>
            @endforelse

        </div>


        @if ($budgets->hasPages())
            <div>
                {{ $budgets->links() }}
            </div>
        @endif

    </div>

@endsection
