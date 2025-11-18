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
        Schema::create('ga_asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->uuid('maintenanceId')->unique();
            $table->date('maintenance_date');
            $table->foreignId('asset_id')->constrained('ga_assets');
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('division_id')->constrained('employee_divisions');
            $table->string('maintenance_condition');
            $table->string('maintenance_repair');
            $table->time('maintenance_start_time');
            $table->time('maintenance_end_time');
            $table->foreignId('pic_id')->constrained('users');
            $table->foreignId('reviewer_id')->constrained('users');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_asset_maintenances');
    }
};
