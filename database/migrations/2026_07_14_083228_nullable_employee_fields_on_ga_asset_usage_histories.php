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
        Schema::table('ga_asset_usage_histories', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->change();
            $table->foreignId('division_id')->nullable()->change();
            $table->foreignId('employee_id')->nullable()->change();
            $table->foreignId('position_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ga_asset_usage_histories', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable(false)->change();
            $table->foreignId('division_id')->nullable(false)->change();
            $table->foreignId('employee_id')->nullable(false)->change();
            $table->foreignId('position_id')->nullable(false)->change();
        });
    }
};
