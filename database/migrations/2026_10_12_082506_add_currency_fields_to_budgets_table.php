<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::table('budgets', function (Blueprint $table) {
        $table->string('currency', 3)->after('amount');
        $table->decimal('exchange_rate', 18, 8)->default(1)->after('currency');
        $table->string('base_currency', 3)->after('exchange_rate');
        $table->decimal('base_amount', 15, 2)->after('base_currency');
    });
}

public function down(): void
{
    Schema::table('budgets', function (Blueprint $table) {
        $table->dropColumn([
            'currency',
            'exchange_rate',
            'base_currency',
            'base_amount',
        ]);
    });
}
};