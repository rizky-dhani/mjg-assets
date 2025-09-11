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
        Schema::create('it_asset_usage_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('usageId')->unique();
            $table->foreignId('asset_id')->constrained('it_assets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('asset_location_id')->nullable()->constrained('it_asset_locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('department_id')->nullable()->constrained('employee_departments')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('division_id')->nullable()->constrained('employee_divisions')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('position_id')->nullable()->constrained('employee_positions')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('i_t_asset_usage_histories');
    }
};
