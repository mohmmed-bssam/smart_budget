@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ __('messages.goals') }}
            </h2>

            <p class="mt-1 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.goals_description') }}
            </p>
        </div>

        <a
            href="{{ route('goals.create') }}"
            class="inline-flex items-center justify-center rounded-xl
                   px-4 py-2.5 text-sm font-medium text-white transition"
            style="background: linear-gradient(135deg, #5E4029, #8A6238);"
        >
            <i class="fa-solid fa-plus me-2"></i>
            {{ __('messages.add_goal') }}
        </a>

    </div>


    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

        @forelse($goals as $goal)

            @php
                $saved = $goal->savings()->sum('amount');

                $remaining = max(
                    $goal->target_amount - $saved,
                    0
                );

                $percentage =
                    $goal->target_amount > 0
                        ? round(($saved / $goal->target_amount) * 100, 2)
                        : 0;

                $progressWidth = min($percentage, 100);
            @endphp

            <div
                class="rounded-2xl border p-5
                       border-[#E8DDCF] bg-[#FFFDF9]
                       dark:border-[#463429] dark:bg-[#241B16]"
            >

                <div class="flex items-start justify-between gap-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl
                                   bg-[#F3E8D8] text-[#6B4A2D]
                                   dark:bg-[#30231B] dark:text-[#D8B77A]"
                        >
                            <i class="fa-solid fa-bullseye"></i>
                        </div>

                        <div>

                            <h3 class="font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                                {{ $goal->title }}
                            </h3>

                            <p class="mt-1 text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                                {{ __('messages.status_' . $goal->status) }}
                            </p>

                        </div>

                    </div>

                </div>


                <div class="mt-6">

                    <p class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                        {{ __('messages.target_amount') }}
                    </p>

                    <p class="mt-1 text-2xl font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                        {{ number_format($goal->target_amount, 2) }}

                        <span class="text-base font-medium text-[#8A7A68] dark:text-[#C4B3A1]">
                            {{ $goal->currency }}
                        </span>
                    </p>

                </div>


                <div class="mt-5">

                    <div class="mb-2 flex items-center justify-between">

                        <span class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                            {{ __('messages.goal_progress') }}
                        </span>

                        <span class="text-sm font-semibold text-[#6B4A2D] dark:text-[#D8B77A]">
                            {{ number_format($percentage, 1) }}%
                        </span>

                    </div>

                    <div class="h-2.5 w-full overflow-hidden rounded-full bg-[#F3E8D8] dark:bg-[#30231B]">

                        <div
                            class="h-full rounded-full bg-[#B88945] transition-all"
                            style="width: {{ $progressWidth }}%;"
                        ></div>

                    </div>

                </div>


                <div class="mt-5 grid grid-cols-2 gap-3">

                    <div class="rounded-xl bg-[#EEF4EF] p-3 dark:bg-[#233329]">

                        <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                            {{ __('messages.saved_amount') }}
                        </p>

                        <p class="mt-1 font-semibold text-[#4F7A5A] dark:text-[#7EAA89]">
                            {{ number_format($saved, 2) }}
                            {{ $goal->currency }}
                        </p>

                    </div>

                    <div class="rounded-xl bg-[#F8F3EC] p-3 dark:bg-[#2A1F18]">

                        <p class="text-xs text-[#8A7A68] dark:text-[#C4B3A1]">
                            {{ __('messages.remaining') }}
                        </p>

                        <p class="mt-1 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                            {{ number_format($remaining, 2) }}
                            {{ $goal->currency }}
                        </p>

                    </div>

                </div>


                @if($goal->deadline)

                    <div class="mt-4 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">

                        <i class="fa-regular fa-calendar me-1"></i>

                        {{ __('messages.deadline') }}:

                        {{ $goal->deadline->format('Y-m-d') }}

                    </div>

                @endif


                <div
                    class="mt-5 flex flex-wrap gap-2 border-t pt-4
                           border-[#EEE2D5] dark:border-[#463429]"
                >

                    <a
                        href="{{ route('goals.show', $goal) }}"
                        class="rounded-lg border px-3 py-2 text-xs font-medium
                               border-[#D8CEC2] text-[#6B4A2D]
                               hover:bg-[#F3E8D8]
                               dark:border-[#594236] dark:text-[#D8B77A]
                               dark:hover:bg-[#30231B]"
                    >
                        {{ __('messages.view') }}
                    </a>

                    <a
                        href="{{ route('goals.edit', $goal) }}"
                        class="rounded-lg border px-3 py-2 text-xs font-medium
                               border-[#D8CEC2] text-[#6B4A2D]
                               hover:bg-[#F3E8D8]
                               dark:border-[#594236] dark:text-[#D8B77A]
                               dark:hover:bg-[#30231B]"
                    >
                        {{ __('messages.edit') }}
                    </a>

                    <form
                        method="POST"
                        action="{{ route('goals.destroy', $goal) }}"
                        onsubmit="return confirm(@js(__('messages.delete_goal_confirmation')))"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-lg border px-3 py-2 text-xs font-medium
                                   border-[#E6BDBD] text-[#B85C5C]
                                   hover:bg-[#F8EAEA]
                                   dark:border-[#674040] dark:text-[#DF7E7E]
                                   dark:hover:bg-[#3A2222]"
                        >
                            {{ __('messages.delete') }}
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div
                class="col-span-full rounded-2xl border p-10 text-center
                       border-[#E8DDCF] bg-[#FFFDF9]
                       dark:border-[#463429] dark:bg-[#241B16]"
            >

                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl
                           bg-[#F3E8D8] text-[#6B4A2D]
                           dark:bg-[#30231B] dark:text-[#D8B77A]"
                >
                    <i class="fa-solid fa-bullseye text-xl"></i>
                </div>

                <h3 class="mt-4 font-semibold text-[#2B2118] dark:text-[#F7F1E8]">
                    {{ __('messages.no_goals_found') }}
                </h3>

                <p class="mt-2 text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                    {{ __('messages.no_goals_description') }}
                </p>

                <a
                    href="{{ route('goals.create') }}"
                    class="mt-5 inline-flex rounded-xl px-4 py-2.5
                           text-sm font-medium text-white"
                    style="background: linear-gradient(135deg, #5E4029, #8A6238);"
                >
                    {{ __('messages.add_first_goal') }}
                </a>

            </div>

        @endforelse

    </div>


    @if($goals->hasPages())
        <div>
            {{ $goals->links() }}
        </div>
    @endif

</div>

@endsection