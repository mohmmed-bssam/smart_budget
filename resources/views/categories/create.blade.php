@extends('layouts.app')
@section('title', __('messages.create_category_title'))

@section('content')

<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ __('messages.add_category') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('messages.add_category_description') }}
        </p>
    </div>


    <div
        class="rounded-2xl border border-gray-200 bg-white p-6
               dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <form
            method="POST"
            action="{{ route('categories.store') }}"
            class="space-y-6"
        >
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Arabic Name --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.category_name_ar') }}
                    </label>

                    <input
                        type="text"
                        name="name_ar"
                        value="{{ old('name_ar') }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:text-white/90"
                    >

                    @error('name_ar')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- English Name --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.category_name_en') }}
                    </label>

                    <input
                        type="text"
                        name="name_en"
                        value="{{ old('name_en') }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:text-white/90"
                    >

                    @error('name_en')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Type --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.type') }}
                    </label>

                    <select
                        name="type"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >
                        <option value="">
                            {{ __('messages.select_category_type') }}
                        </option>

                        <option value="income" @selected(old('type') === 'income')>
                            {{ __('messages.income') }}
                        </option>

                        <option value="expense" @selected(old('type') === 'expense')>
                            {{ __('messages.expense') }}
                        </option>
                    </select>

                    @error('type')
                        <p class="mt-1 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Icon --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.icon') }}
                    </label>

                    <input
                        type="text"
                        name="icon"
                        value="{{ old('icon') }}"
                        placeholder="{{ __('messages.icon_placeholder') }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               outline-none focus:border-brand-500
                               dark:border-gray-700 dark:text-white/90"
                    >
                </div>



            </div>


            <div
                class="flex justify-end gap-3 border-t border-gray-100
                       pt-6 dark:border-gray-800"
            >

                <a
                    href="{{ route('categories.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5
                           text-sm font-medium text-gray-700
                           hover:bg-gray-50
                           dark:border-gray-700 dark:text-gray-300"
                >
                    {{ __('messages.cancel') }}
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5
                           text-sm font-medium text-white
                           hover:bg-brand-600"
                >
                    {{ __('messages.save_category') }}
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
