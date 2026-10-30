<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('savings', function (Blueprint $table) {


            $table->string('currency', 3)
                ->after('amount');

            $table->decimal('source_amount', 15, 2)
                ->after('currency');

            $table->string('source_currency', 3)
                ->after('source_amount');

            $table->decimal('exchange_rate', 18, 8)
                ->default(1)
                ->after('source_currency');
        });
    }


    public function down(): void
    {
        Schema::table('savings', function (Blueprint $table) {

            $table->dropForeign(['account_id']);

            $table->dropColumn([
                'currency',
                'source_amount',
                'source_currency',
                'exchange_rate',
            ]);
        });
    }
};
