<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('from_account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            $table->foreignId('to_account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            $table->decimal('from_amount', 15, 2);

            $table->string('from_currency', 3);

            $table->decimal('to_amount', 15, 2);

            $table->string('to_currency', 3);

            $table->decimal('exchange_rate', 18, 8)
                ->default(1);

            $table->date('transfer_date');

            $table->text('note')
                ->nullable();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
