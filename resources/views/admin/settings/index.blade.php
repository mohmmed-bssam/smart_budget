@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ __('messages.settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('messages.settings_description') }}
        </p>
    </div>


    <div
        class="rounded-2xl border border-gray-200 bg-white p-6
               dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <form
            method="POST"
            action="{{ route('admin.settings.update') }}"
            class="space-y-6"
        >

            @csrf
            @method('PUT')


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- App Name --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.app_name') }}
                    </label>

                    <input
                        type="text"
                        name="app_name"
                        value="{{ old('app_name', $settings['app_name'] ?? 'SmartBudget') }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 outline-none
                               focus:border-brand-500
                               dark:border-gray-700 dark:text-white/90"
                    >

                    @error('app_name')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Default Currency --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.default_currency') }}
                    </label>

                    <select
                        name="default_currency"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 outline-none
                               focus:border-brand-500
                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >

                        @foreach(['USD', 'EUR', 'ILS', 'JOD', 'GBP'] as $currency)

                            <option
                                value="{{ $currency }}"
                                @selected(
                                    old(
                                        'default_currency',
                                        $settings['default_currency'] ?? 'USD'
                                    ) === $currency
                                )
                            >
                                {{ $currency }}
                            </option>

                        @endforeach

                    </select>
                </div>


                {{-- Support Email --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.support_email') }}
                    </label>

                    <input
                        type="email"
                        name="support_email"
                        value="{{ old('support_email', $settings['support_email'] ?? '') }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 outline-none
                               focus:border-brand-500
                               dark:border-gray-700 dark:text-white/90"
                    >

                    @error('support_email')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Items Per Page --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.items_per_page') }}
                    </label>

                    <select
                        name="items_per_page"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4
                               text-sm text-gray-800 outline-none
                               focus:border-brand-500
                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >

                        @foreach([5, 10, 15, 25, 50] as $number)

                            <option
                                value="{{ $number }}"
                                @selected(
                                    old(
                                        'items_per_page',
                                        $settings['items_per_page'] ?? 10
                                    ) == $number
                                )
                            >
                                {{ $number }}
                            </option>

                        @endforeach

                    </select>
                </div>

            </div>


            {{-- Registration --}}
            <div class="border-t border-gray-100 pt-6 dark:border-gray-800">

                <label class="flex cursor-pointer items-center gap-3">

                    <input
                        type="checkbox"
                        name="registration_enabled"
                        value="1"
                        class="h-5 w-5 rounded border-gray-300"
                        @checked(
                            old(
                                'registration_enabled',
                                $settings['registration_enabled'] ?? '1'
                            ) == '1'
                        )
                    >

                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ __('messages.registration_enabled') }}
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __('messages.registration_enabled_description') }}
                        </p>
                    </div>

                </label>

            </div>


            {{-- Save --}}
            <div class="flex justify-end">

                <button
                    type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-3
                           text-sm font-medium text-white
                           hover:bg-brand-600"
                >
                    {{ __('messages.save_changes') }}
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
