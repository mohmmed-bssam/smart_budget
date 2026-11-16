<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
>

<head>

    <meta charset="UTF-8">

    <style>

        body {
            font-family: dejavusans;
            font-size: 12px;
            color: #2B2118;
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
        }


        .header {
            border-bottom: 2px solid #6B4A2D;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }


        .brand {
            font-size: 22px;
            font-weight: bold;
            color: #6B4A2D;
        }


        .title {
            margin-top: 8px;
            font-size: 20px;
            font-weight: bold;
        }


        .muted {
            color: #8A7A68;
        }


        .info-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }


        .info-table td {
            width: 25%;
            padding: 10px;
            border: 1px solid #E8DDCF;
            background: #FFFDF9;
        }


        .info-label {
            display: block;
            font-size: 10px;
            color: #8A7A68;
            margin-bottom: 4px;
        }


        .info-value {
            font-size: 13px;
            font-weight: bold;
        }


        .summary-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }


        .summary-table td {
            width: 20%;
            padding: 12px 8px;
            border: 1px solid #E8DDCF;
            text-align: center;
        }


        .summary-label {
            font-size: 10px;
            color: #8A7A68;
            margin-bottom: 5px;
        }


        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }


        .incoming {
            color: #4F7A5A;
        }


        .outgoing {
            color: #B85C5C;
        }


        .movements {
            width: 100%;
            border-collapse: collapse;
        }


        .movements th {
            padding: 9px 7px;
            background: #F3E8D8;
            color: #6B4A2D;
            border: 1px solid #E8DDCF;
            font-size: 10px;
        }


        .movements td {
            padding: 9px 7px;
            border: 1px solid #E8DDCF;
            vertical-align: top;
        }


        .movement-title {
            font-weight: bold;
            margin-bottom: 3px;
        }


        .movement-details {
            font-size: 10px;
            color: #8A7A68;
        }


        .amount-in {
            color: #4F7A5A;
            font-weight: bold;
            white-space: nowrap;
        }


        .amount-out {
            color: #B85C5C;
            font-weight: bold;
            white-space: nowrap;
        }


        .original {
            margin-top: 3px;
            font-size: 9px;
            color: #8A7A68;
        }


        .empty {
            padding: 30px;
            text-align: center;
            color: #8A7A68;
            border: 1px solid #E8DDCF;
        }


        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #E8DDCF;
            font-size: 9px;
            color: #8A7A68;
            text-align: center;
        }

    </style>

</head>


<body>


    {{-- Header --}}
    <div class="header">

        <div class="brand">
            SmartBudget
        </div>

        <div class="title">
            {{ __('messages.account_statement') }}
        </div>

        <div class="muted">
            {{ __('messages.statement_period') }}:
            {{ $from }}
            —
            {{ $to }}
        </div>

    </div>



    {{-- Account Information --}}
    <table class="info-table">

        <tr>

            <td>

                <span class="info-label">
                    {{ __('messages.account_name') }}
                </span>

                <span class="info-value">

                    {{ $account->name[
                        app()->getLocale()
                    ]
                        ?? $account->name['en']
                        ?? '' }}

                </span>

            </td>


            <td>

                <span class="info-label">
                    {{ __('messages.currency') }}
                </span>

                <span class="info-value">
                    {{ $account->currency }}
                </span>

            </td>


            <td>

                <span class="info-label">
                    {{ __('messages.opening_balance') }}
                </span>

                <span class="info-value">

                    {{ number_format(
                        $openingBalance,
                        2
                    ) }}

                    {{ $account->currency }}

                </span>

            </td>


            <td>

                <span class="info-label">
                    {{ __('messages.statement_generated_at') }}
                </span>

                <span class="info-value">
                    {{ now()->format('Y-m-d') }}
                </span>

            </td>

        </tr>

    </table>



    {{-- Summary --}}
    <table class="summary-table">

        <tr>

            {{-- Opening Balance --}}
            <td>

                <div class="summary-label">
                    {{ __('messages.opening_balance') }}
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $openingBalance,
                        2
                    ) }}

                    {{ $account->currency }}

                </div>

            </td>


            {{-- Incoming --}}
            <td>

                <div class="summary-label">
                    {{ __('messages.total_incoming') }}
                </div>

                <div class="summary-value incoming">

                    +
                    {{ number_format(
                        $totalIncoming,
                        2
                    ) }}

                    {{ $account->currency }}

                </div>

            </td>


            {{-- Outgoing --}}
            <td>

                <div class="summary-label">
                    {{ __('messages.total_outgoing') }}
                </div>

                <div class="summary-value outgoing">

                    -
                    {{ number_format(
                        $totalOutgoing,
                        2
                    ) }}

                    {{ $account->currency }}

                </div>

            </td>


            {{-- Net Movement --}}
            <td>

                <div class="summary-label">
                    {{ __('messages.net_movement') }}
                </div>

                <div
                    class="summary-value
                           {{ $netMovement >= 0
                                ? 'incoming'
                                : 'outgoing' }}"
                >

                    {{ $netMovement >= 0
                        ? '+'
                        : '' }}

                    {{ number_format(
                        $netMovement,
                        2
                    ) }}

                    {{ $account->currency }}

                </div>

            </td>


            {{-- Closing Balance --}}
            <td>

                <div class="summary-label">
                    {{ __('messages.closing_balance') }}
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $closingBalance,
                        2
                    ) }}

                    {{ $account->currency }}

                </div>

            </td>

        </tr>

    </table>



    {{-- Movements --}}
    @if($movements->isNotEmpty())

        <table class="movements">

            <thead>

                <tr>

                    <th>
                        {{ __('messages.date') }}
                    </th>

                    <th>
                        {{ __('messages.description') }}
                    </th>

                    <th>
                        {{ __('messages.movement_type') }}
                    </th>

                    <th>
                        {{ __('messages.amount') }}
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($movements as $movement)

                    <tr>

                        {{-- Date --}}
                        <td>

                            {{ $movement['date']
                                ? \Carbon\Carbon::parse(
                                    $movement['date']
                                )->format('Y-m-d')
                                : '-' }}

                        </td>


                        {{-- Description --}}
                        <td>

                            <div class="movement-title">
                                {{ $movement['title'] }}
                            </div>


                            @if($movement['details'])

                                <div class="movement-details">
                                    {{ $movement['details'] }}
                                </div>

                            @endif


                            @if(
                                $movement['original_amount']
                                &&
                                $movement['original_currency']
                                &&
                                $movement['original_currency']
                                    !==
                                    $movement['currency']
                            )

                                <div class="original">

                                    {{ __('messages.original_transaction') }}:

                                    {{ number_format(
                                        $movement['original_amount'],
                                        2
                                    ) }}

                                    {{ $movement['original_currency'] }}

                                </div>

                            @endif

                        </td>


                        {{-- Type --}}
                        <td>

                            @switch($movement['type'])

                                @case('income')

                                    {{ __('messages.income') }}

                                @break


                                @case('expense')

                                    {{ __('messages.expense') }}

                                @break


                                @case('transfer_in')

                                    {{ __('messages.incoming_transfer') }}

                                @break


                                @case('transfer_out')

                                    {{ __('messages.outgoing_transfer') }}

                                @break


                                @case('saving')

                                    {{ __('messages.saving_movement') }}

                                @break


                                @default

                                    {{ $movement['type'] }}

                            @endswitch

                        </td>


                        {{-- Amount --}}
                        <td
                            class="
                                {{ $movement['direction'] === 'in'
                                    ? 'amount-in'
                                    : 'amount-out' }}
                            "
                        >

                            {{ $movement['direction'] === 'in'
                                ? '+'
                                : '-' }}

                            {{ number_format(
                                $movement['amount'],
                                2
                            ) }}

                            {{ $movement['currency'] }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>


    @else

        <div class="empty">

            {{ __('messages.no_movements_in_period') }}

        </div>

    @endif



    <div class="footer">

        SmartBudget —
        {{ __('messages.account_statement') }}

    </div>


</body>

</html>
