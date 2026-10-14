@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ __('messages.transfers') }}
            </h2>

            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.transfers_description') }}
            </p>
        </div>

        <a
            href="{{ route('transfers.create') }}"
            class="inline-flex items-center justify-center rounded-xl
                   px-4 py-2.5 text-sm font-medium text-white"
            style="background: linear-gradient(135deg, #5E4029, #8A6238);"
        >
            <i class="fa-solid fa-right-left me-2"></i>
            {{ __('messages.new_transfer') }}
        </a>

    </div>


    <div
        class="overflow-hidden rounded-2xl border
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead
                    class="bg-[#F8F3EC]
                           dark:bg-[#30231B]"
                >
                    <tr class="text-start text-xs text-[#8A7A68] dark:text-[#C4B3A1]">

                        <th class="px-5 py-3 text-start">
                            {{ __('messages.from_account') }}
                        </th>

                        <th class="px-5 py-3 text-start">
                            {{ __('messages.to_account') }}
                        </th>

                        <th class="px-5 py-3 text-start">
                            {{ __('messages.sent_amount') }}
                        </th>

                        <th class="px-5 py-3 text-start">
                            {{ __('messages.received_amount') }}
                        </th>

                        <th class="px-5 py-3 text-start">
                            {{ __('messages.transfer_date') }}
                        </th>

                        <th class="px-5 py-3 text-start">
                            {{ __('messages.actions') }}
                        </th>

                    </tr>
                </thead>


                <tbody class="divide-y divide-[#EEE2D5] dark:divide-[#463429]">

                    @forelse($transfers as $transfer)

                        <tr class="transition hover:bg-[#FAF6F0] dark:hover:bg-[#2A1F18]">

                            <td class="px-5 py-4">

                                <div class="font-medium text-[#2B2118] dark:text-[#F7F1E8]">

                                    {{ $transfer->fromAccount?->name[app()->getLocale()]
                                        ?? $transfer->fromAccount?->name['en']
                                        ?? '-' }}

                                </div>

                                <div class="mt-1 text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                                    {{ $transfer->from_currency }}
                                </div>

                            </td>


                            <td class="px-5 py-4">

                                <div class="font-medium text-[#2B2118] dark:text-[#F7F1E8]">

                                    {{ $transfer->toAccount?->name[app()->getLocale()]
                                        ?? $transfer->toAccount?->name['en']
                                        ?? '-' }}

                                </div>

                                <div class="mt-1 text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                                    {{ $transfer->to_currency }}
                                </div>

                            </td>


                            <td class="px-5 py-4 font-semibold text-[#B85C5C] dark:text-[#DF7E7E]">

                                - {{ number_format($transfer->from_amount, 2) }}
                                {{ $transfer->from_currency }}

                            </td>


                            <td class="px-5 py-4 font-semibold text-[#4F7A5A] dark:text-[#7EAA89]">

                                + {{ number_format($transfer->to_amount, 2) }}
                                {{ $transfer->to_currency }}

                            </td>


                            <td class="px-5 py-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ $transfer->transfer_date?->format('Y-m-d') }}
                            </td>


                            <td class="px-5 py-4">

                                <div class="flex flex-wrap gap-2">

                                    <a
                                        href="{{ route('transfers.show', $transfer) }}"
                                        class="rounded-lg border
                                               border-[#D8CEC2]
                                               px-3 py-2
                                               text-xs font-medium
                                               text-[#6B4A2D]
                                               hover:bg-[#F3E8D8]

                                               dark:border-[#594236]
                                               dark:text-[#D8B77A]
                                               dark:hover:bg-[#30231B]"
                                    >
                                        {{ __('messages.view') }}
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('transfers.destroy', $transfer) }}"
                                        onsubmit="return confirm(@js(__('messages.reverse_transfer_confirmation')))"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg border
                                                   border-[#E6BDBD]
                                                   px-3 py-2
                                                   text-xs font-medium
                                                   text-[#B85C5C]
                                                   hover:bg-[#F8EAEA]

                                                   dark:border-[#674040]
                                                   dark:text-[#DF7E7E]
                                                   dark:hover:bg-[#3A2222]"
                                        >
                                            {{ __('messages.reverse_transfer') }}
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">

                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center
                                           rounded-2xl bg-[#F3E8D8]
                                           text-[#6B4A2D]
                                           dark:bg-[#30231B]
                                           dark:text-[#D8B77A]"
                                >
                                    <i class="fa-solid fa-right-left text-xl"></i>
                                </div>

                                <h3 class="mt-4 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                                    {{ __('messages.no_transfers_found') }}
                                </h3>

                                <p class="mt-2 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                                    {{ __('messages.no_transfers_description') }}
                                </p>

                                <a
                                    href="{{ route('transfers.create') }}"
                                    class="mt-5 inline-flex rounded-xl
                                           px-4 py-2.5 text-sm
                                           font-medium text-white"
                                    style="background: linear-gradient(135deg, #5E4029, #8A6238);"
                                >
                                    {{ __('messages.new_transfer') }}
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    @if($transfers->hasPages())
        <div>
            {{ $transfers->links() }}
        </div>
    @endif

</div>

@endsection
