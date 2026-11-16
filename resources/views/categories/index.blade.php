@extends('layouts.app')
@section('title', __('messages.categories'))
@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ __('messages.categories') }}
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.categories_description') }}
                </p>
            </div>

            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center justify-center rounded-lg
                   bg-brand-500 px-4 py-2.5 text-sm font-medium
                   text-white hover:bg-brand-600">
                {{ __('messages.add_category') }}
            </a>

        </div>


        @forelse($categories as $category)
            @if ($loop->first)
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @endif

            <div
                class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]">

                <div class="flex items-start justify-between gap-3">

                    <div class="flex items-center gap-3">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">

                            @if (!empty($category->icon))
                                <span class="text-xl">
                                    <i class="{{ $category->icon ?? 'fa-solid fa-utensils' }}"></i>

                                </span>
                            @else
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                                    class="text-gray-700 dark:text-gray-300">
                                    <path d="M4 4H10V10H4V4ZM14 4H20V10H14V4ZM4 14H10V20H4V14ZM14 14H20V20H14V14Z" />
                                </svg>
                            @endif

                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                {{ $category->name[app()->getLocale()] ?? ($category->name['en'] ?? '') }}
                            </h3>

                            <span class="text-sm text-gray-500 dark:text-gray-400">

                                @if ($category->type === 'income')
                                    {{ __('messages.income') }}
                                @else
                                    {{ __('messages.expense') }}
                                @endif

                            </span>
                        </div>

                    </div>


                    @if ($category->user_id === null)
                        <span
                            class="rounded-full bg-gray-100 px-2.5 py-1
                               text-theme-xs font-medium text-gray-600
                               dark:bg-gray-800 dark:text-gray-300">
                            {{ __('messages.system_category') }}
                        </span>
                    @else
                        <span
                            class="rounded-full bg-brand-50 px-2.5 py-1
                               text-theme-xs font-medium text-brand-600
                               dark:bg-brand-500/15 dark:text-brand-400">
                            {{ __('messages.personal_category') }}
                        </span>
                    @endif

                </div>


                <div class="mt-6 border-t border-gray-100 pt-4 dark:border-gray-800">

                    <div class="flex flex-wrap items-center gap-2">

                        <a href="{{ route('categories.show', $category) }}"
                            class="rounded-lg border border-gray-300 px-3 py-2
                               text-theme-xs font-medium text-gray-700
                               hover:bg-gray-50
                               dark:border-gray-700 dark:text-gray-300
                               dark:hover:bg-white/[0.03]">
                            {{ __('messages.view') }}
                        </a>


                        @if ($category->user_id !== null)
                            <a href="{{ route('categories.edit', $category) }}"
                                class="rounded-lg border border-gray-300 px-3 py-2
                                   text-theme-xs font-medium text-gray-700
                                   hover:bg-gray-50
                                   dark:border-gray-700 dark:text-gray-300
                                   dark:hover:bg-white/[0.03]">
                                {{ __('messages.edit') }}
                            </a>

                            <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                onsubmit="return confirm(@js(__('messages.delete_category_confirmation')))">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="rounded-lg border border-error-300 px-3 py-2
                                       text-theme-xs font-medium text-error-600
                                       hover:bg-error-50
                                       dark:border-error-800 dark:text-error-500
                                       dark:hover:bg-error-500/10">
                                    {{ __('messages.delete') }}
                                </button>
                            </form>
                        @endif

                    </div>

                </div>

            </div>

            @if ($loop->last)
    </div>
    @endif

@empty

    <div
        class="rounded-2xl border border-dashed border-gray-300 bg-white
                   px-6 py-14 text-center
                   dark:border-gray-700 dark:bg-white/[0.03]">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ __('messages.no_categories_found') }}
        </h3>

        <p class="mx-auto mt-2 max-w-md text-sm text-gray-500 dark:text-gray-400">
            {{ __('messages.no_categories_description') }}
        </p>

        <a href="{{ route('categories.create') }}"
            class="mt-5 inline-flex rounded-lg bg-brand-500
                       px-4 py-2.5 text-sm font-medium text-white
                       hover:bg-brand-600">
            {{ __('messages.add_first_category') }}
        </a>

    </div>
    @endforelse


    @if ($categories->hasPages())
        <div>
            {{ $categories->links() }}
        </div>
    @endif

    </div>
@endsection
