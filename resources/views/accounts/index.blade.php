@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                {{ __('messages.accounts') }}
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.accounts_description') }}
            </p>
        </div>
<div class="flex flex-wrap items-center gap-3">

    <a
        href="{{ route('accounts.create') }}"
        class="inline-flex items-center justify-center rounded-xl
               px-4 py-2.5 text-sm font-medium text-white"
        style="background: linear-gradient(135deg, #5E4029, #8A6238);"
    >
        <i class="fa-solid fa-plus me-2"></i>

        {{ __('messages.add_account') }}
    </a>


    <a
        href="{{ route('transfers.create') }}"
        class="inline-flex items-center justify-center rounded-xl
               border border-[#D8CEC2]
               bg-[#FFFDF9]
               px-4 py-2.5
               text-sm font-medium
               text-[#6B4A2D]
               transition
               hover:bg-[#F3E8D8]

               dark:border-[#594236]
               dark:bg-[#241B16]
               dark:text-[#D8B77A]
               dark:hover:bg-[#30231B]"
    >
        <i class="fa-solid fa-right-left me-2"></i>

        {{ __('messages.transfer') }}
    </a>

</div>
        

    </div>


    {{-- Accounts --}}
    @forelse($accounts as $account)

        @if($loop->first)
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        @endif

        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >

            {{-- Top --}}
            <div class="flex items-start justify-between gap-3">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-xl bg-gray-100 dark:bg-gray-800"
                    >
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            class="fill-gray-800 dark:fill-white/90"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M4 6.5C4 5.11929 5.11929 4 6.5 4H17.5C18.8807 4 20 5.11929 20 6.5V17.5C20 18.8807 18.8807 20 17.5 20H6.5C5.11929 20 4 18.8807 4 17.5V6.5ZM6.5 5.5C5.94772 5.5 5.5 5.94772 5.5 6.5V8H18.5V6.5C18.5 5.94772 18.0523 5.5 17.5 5.5H6.5ZM5.5 9.5V17.5C5.5 18.0523 5.94772 18.5 6.5 18.5H17.5C18.0523 18.5 18.5 18.0523 18.5 17.5V9.5H5.5Z"
                            />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            {{ $account->name[app()->getLocale()] ?? $account->name['en'] }}
                        </h3>

                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            @switch($account->type)

                                @case('cash')
                                    {{ __('messages.account_type_cash') }}
                                    @break

                                @case('bank')
                                    {{ __('messages.account_type_bank') }}
                                    @break

                                @case('wallet')
                                    {{ __('messages.account_type_wallet') }}
                                    @break

                                @case('savings')
                                    {{ __('messages.account_type_savings') }}
                                    @break

                                @default
                                    {{ ucfirst($account->type) }}

                            @endswitch
                        </span>
                    </div>

                </div>

                {{-- Currency Badge --}}
                <span
                    class="rounded-full bg-brand-50 px-2.5 py-1
                           text-theme-xs font-medium text-brand-600
                           dark:bg-brand-500/15 dark:text-brand-400"
                >
                    {{ strtoupper($account->currency) }}
                </span>

            </div>


            {{-- Balance --}}
            <div class="mt-6">

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.current_balance') }}
                </p>

                <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($account->balance, 2) }}
                    <span class="text-base font-medium text-gray-500 dark:text-gray-400">
                        {{ strtoupper($account->currency) }}
                    </span>
                </h4>

            </div>


            {{-- Actions --}}
            <div
                class="mt-6 flex flex-wrap items-center gap-2
                       border-t border-gray-100 pt-4 dark:border-gray-800"
            >

                {{-- View --}}
                <a
                    href="{{ route('accounts.show', $account) }}"
                    class="rounded-lg border border-gray-300 px-3 py-2
                           text-theme-xs font-medium text-gray-700
                           hover:bg-gray-50
                           dark:border-gray-700 dark:text-gray-300
                           dark:hover:bg-white/[0.03]"
                >
                    {{ __('messages.view') }}
                </a>


                {{-- Edit --}}
                <a
                    href="{{ route('accounts.edit', $account) }}"
                    class="rounded-lg border border-gray-300 px-3 py-2
                           text-theme-xs font-medium text-gray-700
                           hover:bg-gray-50
                           dark:border-gray-700 dark:text-gray-300
                           dark:hover:bg-white/[0.03]"
                >
                    {{ __('messages.edit') }}
                </a>


                {{-- Delete --}}
                <form
                    method="POST"
                    action="{{ route('accounts.destroy', $account) }}"
                    onsubmit="return confirm(@js(__('messages.delete_account_confirmation')))"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="rounded-lg border border-error-300 px-3 py-2
                               text-theme-xs font-medium text-error-600
                               hover:bg-error-50
                               dark:border-error-800 dark:text-error-500
                               dark:hover:bg-error-500/10"
                    >
                        {{ __('messages.delete') }}
                    </button>

                </form>

            </div>

        </div>

        @if($loop->last)
            </div>
        @endif


    @empty

        {{-- Empty State --}}
        <div
            class="rounded-2xl border border-dashed border-gray-300 bg-white
                   px-6 py-14 text-center
                   dark:border-gray-700 dark:bg-white/[0.03]"
        >

            <div
                class="mx-auto flex h-14 w-14 items-center justify-center
                       rounded-full bg-gray-100 dark:bg-gray-800"
            >
                <svg
                    width="28"
                    height="28"
                    viewBox="0 0 24 24"
                    fill="none"
                    class="fill-gray-500 dark:fill-gray-400"
                >
                    <path
                        d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9Z"
                    />
                </svg>
            </div>

            <h3 class="mt-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('messages.no_accounts_found') }}
            </h3>

            <p class="mx-auto mt-2 max-w-md text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.no_accounts_description') }}
            </p>

            <a
                href="{{ route('accounts.create') }}"
                class="mt-5 inline-flex rounded-lg bg-brand-500
                       px-4 py-2.5 text-sm font-medium text-white
                       hover:bg-brand-600"
            >
                {{ __('messages.add_first_account') }}
            </a>

        </div>

    @endforelse


    {{-- Pagination --}}
    @if($accounts->hasPages())

        <div>
            {{ $accounts->links() }}
        </div>

    @endif

</div>

@endsection
