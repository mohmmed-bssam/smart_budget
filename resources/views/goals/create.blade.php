@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ __('messages.add_goal') }}
            </h2>

            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.add_goal_description') }}
            </p>
        </div>


        <div
            class="rounded-2xl border p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]">

            <form method="POST" action="{{ route('goals.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div>

                        <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                            {{ __('messages.goal_title') }}
                        </label>

                        <input type="text" name="title" value="{{ old('title') }}"
                            class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]
                               dark:border-[#594236] dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]">

                        @error('title')
                            <p class="mt-1 text-sm text-[#B85C5C]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                            {{ __('messages.target_amount') }}
                        </label>

                        <input type="number" name="target_amount" min="0.01" step="0.01"
                            value="{{ old('target_amount') }}"
                            class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]
                               dark:border-[#594236] dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]">

                        @error('target_amount')
                            <p class="mt-1 text-sm text-[#B85C5C]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                            {{ __('messages.currency') }}
                        </label>

                        <select name="currency"
                            class="h-12 w-full rounded-xl border
                               border-[#D8CEC2] bg-[#FFFDF9]
                               px-4 text-sm text-[#2B2118]
                               outline-none focus:border-[#B88945]
                               dark:border-[#594236] dark:bg-[#2A1F18]
                               dark:text-[#F7F1E8]">

                            <option value="">
                                {{ __('messages.select_currency') }}
                            </option>

                            @foreach (['ILS', 'USD', 'EUR', 'JOD', 'GBP'] as $currency)
                                <option value="{{ $currency }}" @selected(old('currency') === $currency)>
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

                    <div>

                        <label class="mb-2 block text-sm font-medium text-[#3E342C] dark:text-[#E7DDD2]">
                            {{ __('messages.deadline') }}
                        </label>

                        <div class="relative">

                            <input id="deadline" type="date" name="deadline" min="{{ now()->toDateString() }}"
                                value="{{ old('deadline') }}"
                                class="h-12 w-full rounded-xl border
                   border-[#D8CEC2] bg-[#FFFDF9]
                   px-4 pe-12 text-sm text-[#2B2118]
                   outline-none focus:border-[#B88945]
                   dark:border-[#594236] dark:bg-[#2A1F18]
                   dark:text-[#F7F1E8]">

                            <button type="button" onclick="document.getElementById('deadline').showPicker()"
                                class="absolute inset-y-0 end-0
                   flex items-center pe-4
                   text-[#8A6238]
                   hover:text-[#6B4A2D]
                   dark:text-[#D8B77A]
                   dark:hover:text-[#E3C38F]">
                                <i class="fa-regular fa-calendar-days"></i>
                            </button>

                        </div>

                        @error('deadline')
                            <p class="mt-1 text-sm text-[#B85C5C]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                </div>


                <div
                    class="flex justify-end gap-3 border-t pt-6
                       border-[#EEE2D5] dark:border-[#463429]">

                    <a href="{{ route('goals.index') }}"
                        class="rounded-xl border px-5 py-2.5 text-sm font-medium
                           border-[#D8CEC2] text-[#6B4A2D]
                           hover:bg-[#F3E8D8]
                           dark:border-[#594236] dark:text-[#D8B77A]
                           dark:hover:bg-[#30231B]">
                        {{ __('messages.cancel') }}
                    </a>

                    <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-medium text-white"
                        style="background: linear-gradient(135deg, #5E4029, #8A6238);">
                        {{ __('messages.save_goal') }}
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection
