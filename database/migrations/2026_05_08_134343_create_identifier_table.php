<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('identifier', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('IDENTIF', 255);
            $table->string('TIPO_IDENTIFICADOR', 50);
            $table->string('ID_HEX', 10)->default('#FFFFFF');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identifier');
    }
};
