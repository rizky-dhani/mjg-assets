<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ga_asset_usage_histories', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->foreign('asset_id')->references('id')->on('ga_assets')->onDelete('cascade');
        });

        Schema::table('ga_asset_maintenances', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->foreign('asset_id')->references('id')->on('ga_assets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('ga_asset_usage_histories', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->foreign('asset_id')->references('id')->on('ga_assets');
        });

        Schema::table('ga_asset_maintenances', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->foreign('asset_id')->references('id')->on('ga_assets');
        });
    }
};
