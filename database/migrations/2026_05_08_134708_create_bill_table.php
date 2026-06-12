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
        Schema::create('bill', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ID_IDENTIF')->constrained('identifier')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('ID_USR')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('DESCRICAO', 255);
            $table->decimal('VALOR', 10, 2);
            $table->date('VENCIMENTO');
            $table->integer('PARCELAS')->default(0);
            $table->string('STATUS', 20);
            $table->boolean('RECORRENTE')->default(false);
            $table->foreignUuid('ID_REC')->nullable()->constrained('bill')->onUpdate('cascade')->onDelete('cascade');
            $table->date('PAGO_EM')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill');
    }
};
