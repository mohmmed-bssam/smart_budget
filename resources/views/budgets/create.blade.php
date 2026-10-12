@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>

        <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
            {{ __('messages.add_budget') }}
        </h2>

        <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
            {{ __('messages.add_budget_description') }}
        </p>

    </div>


    <div
        class="rounded-2xl border p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <form
            method="POST"
            action="{{ route('budgets.store') }}"
            class="space-y-6"
        >

            @csrf


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Category --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                        {{ __('messages.category') }}
                    </label>

                    <select
                        name="category_id"
                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]
                               dark:border-[#594236] dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                        <option value="">
                            {{ __('messages.select_category') }}
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id') == $category->id)
                            >
                                {{ $category->name[app()->getLocale()]
                                    ?? $category->name['en']
                                    ?? '' }}
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

                    <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                        {{ __('messages.budget_amount') }}
                    </label>

                    <input
                        type="number"
                        name="amount"
                        min="0.01"
                        step="0.01"
                        value="{{ old('amount') }}"
                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]
                               dark:border-[#594236] dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                    @error('amount')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Currency --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                        {{ __('messages.currency') }}
                    </label>

                    <select
                        name="currency"
                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]
                               dark:border-[#594236] dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                        <option value="">
                            {{ __('messages.select_currency') }}
                        </option>

                        @foreach(['ILS', 'USD', 'EUR', 'JOD', 'GBP'] as $currency)

                            <option
                                value="{{ $currency }}"
                                @selected(old('currency') === $currency)
                            >
                                {{ $currency }}
                            </option>

                        @endforeach

                    </select>

                    @error('currency')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Month --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                        {{ __('messages.month') }}
                    </label>

                    <select
                        name="month"
                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]
                               dark:border-[#594236] dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                        @for($month = 1; $month <= 12; $month++)

                            <option
                                value="{{ $month }}"
                                @selected(old('month', now()->month) == $month)
                            >
                                {{ __('messages.month_' . $month) }}
                            </option>

                        @endfor

                    </select>

                    @error('month')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Year --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                        {{ __('messages.year') }}
                    </label>

                    <input
                        type="number"
                        name="year"
                        min="{{ now()->year }}"
                        value="{{ old('year', now()->year) }}"
                        class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]
                               dark:border-[#594236] dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]"
                    >

                    @error('year')
                        <p class="mt-1 text-sm text-[#B85C5C]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            <div
                class="flex justify-end gap-3 border-t pt-6
                       border-[#EEE2D5] dark:border-[#463429]"
            >

                <a
                    href="{{ route('budgets.index') }}"
                    class="rounded-xl border px-5 py-2.5 text-sm font-medium
                           border-[#D8CEC2] text-[#6B4A2D]
                           hover:bg-[#F3E8D8]
                           dark:border-[#594236] dark:text-[#D8B77A]
                           dark:hover:bg-[#30231B]"
                >
                    {{ __('messages.cancel') }}
                </a>


                <button
                    type="submit"
                    class="rounded-xl px-5 py-2.5 text-sm
                           font-medium text-white transition"
                    style="background: linear-gradient(135deg, #5E4029, #8A6238);"
                >
                    {{ __('messages.save_budget') }}
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
