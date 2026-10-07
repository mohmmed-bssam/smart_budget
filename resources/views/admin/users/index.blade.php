@extends('layouts.app')

@section('content')
    <div data-search-page="users" class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ __('messages.users_management') }}
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.users_management_description') }}
                </p>
            </div>
        </div>


        {{-- Users Table --}}
        <div
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white
                   dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="min-w-full">

                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">

                            <th class="px-5 py-4 text-start">
                                <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.user') }}
                                </span>
                            </th>

                            <th class="px-5 py-4 text-start">
                                <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.phone') }}
                                </span>
                            </th>

                            <th class="px-5 py-4 text-start">
                                <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.role') }}
                                </span>
                            </th>

                            <th class="px-5 py-4 text-start">
                                <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.status') }}
                                </span>
                            </th>

                            <th class="px-5 py-4 text-start">
                                <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.joined_at') }}
                                </span>
                            </th>

                            <th class="px-5 py-4 text-start">
                                <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.actions') }}
                                </span>
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $user)
                            <tr class="user-row border-b border-gray-100 dark:border-gray-800"
                                data-search="{{ strtolower(
                                    ($user->name ?? '') .
                                        ' ' .
                                        ($user->email ?? '') .
                                        ' ' .
                                        ($user->phone ?? '') .
                                        ' ' .
                                        ($user->role ?? '') .
                                        ' ' .
                                        ($user->status ? __('messages.active') : __('messages.inactive')),
                                ) }}
                                   ">

                                {{-- User --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full
                                                   bg-gray-100 font-semibold text-gray-700
                                                   dark:bg-gray-800 dark:text-gray-300">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                {{ $user->name }}
                                            </p>

                                            <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                                {{ $user->email }}
                                            </span>
                                        </div>

                                    </div>
                                </td>


                                {{-- Phone --}}
                                <td class="px-5 py-4">
                                    <span class="text-theme-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->phone ?? '-' }}
                                    </span>
                                </td>


                                {{-- Role --}}
                                <td class="px-5 py-4">

                                    @if ($user->role === 'admin')
                                        <span
                                            class="rounded-full bg-brand-50 px-2.5 py-1
                                                   text-theme-xs font-medium text-brand-600
                                                   dark:bg-brand-500/15 dark:text-brand-400">
                                            {{ __('messages.admin') }}
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-gray-100 px-2.5 py-1
                                                   text-theme-xs font-medium text-gray-600
                                                   dark:bg-gray-800 dark:text-gray-300">
                                            {{ __('messages.user') }}
                                        </span>
                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4">

                                    @if ($user->status)
                                        <span
                                            class="rounded-full bg-success-50 px-2.5 py-1
                                                   text-theme-xs font-medium text-success-600
                                                   dark:bg-success-500/15 dark:text-success-500">
                                            {{ __('messages.active') }}
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-error-50 px-2.5 py-1
                                                   text-theme-xs font-medium text-error-600
                                                   dark:bg-error-500/15 dark:text-error-500">
                                            {{ __('messages.inactive') }}
                                        </span>
                                    @endif

                                </td>


                                {{-- Joined --}}
                                <td class="px-5 py-4">
                                    <span class="text-theme-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at?->format('Y-m-d') }}
                                    </span>
                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    @if ($user->id === auth()->id())
                                        <span class="text-theme-xs text-gray-400">
                                            {{ __('messages.current_account') }}
                                        </span>
                                    @else
                                        <div class="flex flex-wrap items-center gap-2">

                                            {{-- Change Role --}}
                                            <form method="POST" action="{{ route('admin.users.update-role', $user) }}"
                                                onsubmit="return confirm(@js(__('messages.change_role_confirmation')))">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="rounded-lg border border-gray-300 px-3 py-2
                                                           text-theme-xs font-medium text-gray-700
                                                           hover:bg-gray-50
                                                           dark:border-gray-700 dark:text-gray-300
                                                           dark:hover:bg-white/[0.03]">
                                                    {{ $user->role === 'admin' ? __('messages.make_user') : __('messages.make_admin') }}
                                                </button>
                                            </form>


                                            {{-- Change Status --}}
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}"
                                                onsubmit="return confirm(@js(__('messages.change_status_confirmation')))">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="rounded-lg border border-gray-300 px-3 py-2
                                                           text-theme-xs font-medium text-gray-700
                                                           hover:bg-gray-50
                                                           dark:border-gray-700 dark:text-gray-300
                                                           dark:hover:bg-white/[0.03]">
                                                    {{ $user->status ? __('messages.deactivate') : __('messages.activate') }}
                                                </button>
                                            </form>


                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm(@js(__('messages.delete_user_confirmation')))">
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

                                        </div>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('messages.no_users_found') }}
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="usersSearchEmpty" class="hidden">
                            <td colspan="6" class="px-5 py-10 text-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('messages.no_search_results') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>

    </div>
@endsection
