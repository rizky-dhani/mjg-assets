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
        Schema::create('ga_assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('assetId')->unique();
            $table->string('asset_name');
            $table->string('asset_code')->unique();
            $table->year('asset_year_bought');
            $table->string('asset_brand');
            $table->string('asset_model');
            $table->string('asset_serial_number');
            $table->foreignId('asset_category_id')->constrained('ga_asset_categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('asset_condition', ['New','First Hand','Used','Defect','Disposed']);
            $table->integer('asset_price');
            $table->integer('asset_sell_price')->nullable();
            $table->text('asset_notes')->nullable();
            $table->text('asset_remarks')->nullable();
            $table->foreignId('asset_location_id')->nullable()->constrained('ga_asset_locations')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('asset_user_id')->nullable()->constrained('employees')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('pic_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('barcode')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_assets');
    }
};
