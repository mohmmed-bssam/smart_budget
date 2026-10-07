@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        <div class="col-span-12">
            <x-ecommerce.ecommerce-metrics :stats="$stats" />
        </div>

        <div class="col-span-12">
            <x-ecommerce.monthly-sale :monthly-income="$monthlyIncome" :monthly-expenses="$monthlyExpenses" :year="$year" />
        </div>

        <div class="col-span-12">
            <x-ecommerce.recent-orders :recent-transactions="$recentTransactions" />
        </div>

    </div>
@endsection
