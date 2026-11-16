@extends('layouts.app')
@section('title', __('messages.admin_dashboard'))
@section('content')

<div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- Metrics --}}
    <div class="col-span-12">

        <x-ecommerce.ecommerce-metrics
            :stats="$stats"
        />

    </div>


    {{-- Monthly Income & Expenses --}}
    <div class="col-span-12">

        <x-ecommerce.monthly-sale
            :monthly-income="$monthlyIncome"
            :monthly-expenses="$monthlyExpenses"
            :year="$year"
            :base-currency="$baseCurrency"
        />

    </div>


    {{-- Recent Transactions --}}
    <div class="col-span-12">

        <x-ecommerce.recent-orders
            :recent-transactions="$recentTransactions"
        />

    </div>

</div>

@endsection
