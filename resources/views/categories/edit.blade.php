@extends('layouts.app')
@section('title', __('messages.edit_category_title'))

@section('content')

<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ __('messages.edit_category') }}
        </h2>
    </div>


    <div
        class="rounded-2xl border border-gray-200 bg-white p-6
               dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <form
            method="POST"
            action="{{ route('categories.update', $category) }}"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.category_name_ar') }}
                    </label>

                    <input
                        type="text"
                        name="name_ar"
                        value="{{ old('name_ar', $category->name['ar'] ?? '') }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:text-white/90"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.category_name_en') }}
                    </label>

                    <input
                        type="text"
                        name="name_en"
                        value="{{ old('name_en', $category->name['en'] ?? '') }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:text-white/90"
                    >
                </div>


                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.type') }}
                    </label>

                    <select
                        name="type"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90"
                    >

                        <option
                            value="income"
                            @selected(old('type', $category->type) === 'income')
                        >
                            {{ __('messages.income') }}
                        </option>

                        <option
                            value="expense"
                            @selected(old('type', $category->type) === 'expense')
                        >
                            {{ __('messages.expense') }}
                        </option>

                    </select>
                </div>


                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('messages.icon') }}
                    </label>

                    <input
                        type="text"
                        name="icon"
                        value="{{ old('icon', $category->icon) }}"
                        class="h-11 w-full rounded-lg border border-gray-300
                               bg-transparent px-4 text-sm text-gray-800
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
                           dark:border-gray-700 dark:text-gray-300"
                >
                    {{ __('messages.cancel') }}
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5
                           text-sm font-medium text-white"
                >
                    {{ __('messages.save_changes') }}
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
