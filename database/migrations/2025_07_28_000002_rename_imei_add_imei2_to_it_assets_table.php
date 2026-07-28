<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('it_assets', function (Blueprint $table) {
            $table->renameColumn('imei', 'imei_1');
            $table->string('imei_2')->after('imei_1')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('it_assets', function (Blueprint $table) {
            $table->dropColumn('imei_2');
            $table->renameColumn('imei_1', 'imei');
        });
    }
};
