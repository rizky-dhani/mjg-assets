<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resource_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('permissionId')->unique();
            $table->string('resource_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_permissions');
    }
};
