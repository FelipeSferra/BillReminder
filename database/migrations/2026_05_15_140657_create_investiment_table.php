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
        Schema::create('investiment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ID_IDENTIF')->constrained('identifier')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('ID_USR')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('DESCRICAO', 255);
            $table->decimal('VALOR', 10, 2);
            /* $table->string('TIPO_OPERACAO')->default('APLICAR');
            $table->date('DATA_OPERACAO'); */
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investiment');
    }
};
