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
        Schema::create('ga_asset_usage_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('usageId')->unique();
            $table->foreignId('asset_id')->constrained('ga_assets');
            $table->foreignId('asset_location_id')->constrained('ga_asset_locations');
            $table->foreignId('department_id')->constrained('employee_departments');
            $table->foreignId('division_id')->constrained('employee_divisions');
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('position_id')->constrained('employee_positions');
            $table->integer('usage_quantity');
            $table->date('usage_start_date');
            $table->date('usage_end_date')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_asset_usage_histories');
    }
};
