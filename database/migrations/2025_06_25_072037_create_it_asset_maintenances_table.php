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
        Schema::create('it_asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->uuid('maintenanceId')->unique();
            $table->date('maintenance_date');
            $table->foreignId('asset_id')->constrained('it_assets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('division_id')->nullable()->constrained('employee_divisions')->onDelete('set null')->onUpdate('cascade');
            $table->string('maintenance_condition', 5000);
            $table->string('maintenance_repair', 5000);
            $table->time('maintenance_start_time');
            $table->time('maintenance_end_time')->nullable();
            $table->foreignId('pic_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it_asset_maintenances');
    }
};
