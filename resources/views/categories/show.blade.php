@extends('layouts.app')
@section('title', __('messages.category_details'))
@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ $category->name[app()->getLocale()] ?? $category->name['en'] ?? '' }}
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.category_details') }}
            </p>
        </div>

        <div class="flex gap-2">

            @if($category->user_id !== null)

                <a
                    href="{{ route('categories.edit', $category) }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5
                           text-sm font-medium text-gray-700
                           dark:border-gray-700 dark:text-gray-300"
                >
                    {{ __('messages.edit') }}
                </a>

            @endif

            <a
                href="{{ route('categories.index') }}"
                class="rounded-lg bg-brand-500 px-4 py-2.5
                       text-sm font-medium text-white"
            >
                {{ __('messages.back_to_categories') }}
            </a>

        </div>

    </div>


    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.category_name') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ $category->name[app()->getLocale()] ?? $category->name['en'] ?? '' }}
            </h3>

        </div>


        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.type') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ $category->type === 'income'
                    ? __('messages.income')
                    : __('messages.expense') }}
            </h3>

        </div>


        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.icon') }}
            </p>

            <div class="mt-2 text-2xl text-gray-800 dark:text-white/90">
                {{ $category->icon ?: '-' }}
            </div>

        </div>


        <div class="rounded-2xl border border-gray-200 bg-white p-5
                    dark:border-gray-800 dark:bg-white/[0.03]">

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.category_scope') }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ $category->user_id === null
                    ? __('messages.system_category')
                    : __('messages.personal_category') }}
            </h3>

        </div>

    </div>

</div>

@endsection
