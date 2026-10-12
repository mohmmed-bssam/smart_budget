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
    Schema::table('savings', function (Blueprint $table) {
        $table->foreignId('account_id')
            ->after('goal_id')
            ->constrained()
            ->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('savings', function (Blueprint $table) {
        $table->dropForeign(['account_id']);
        $table->dropColumn('account_id');
    });
}
};