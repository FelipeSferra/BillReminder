<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('TIPO_CONTA');
            $table->foreign('TIPO_CONTA')->references('id')->on('identifier')->onUpdate('cascade');
            $table->unsignedBigInteger('ID_USR');
            $table->foreign('ID_USR')->references('id')->on('users')->onUpdate('cascade');
            $table->string('DESCRICAO', 255);
            $table->double('VALOR', 10, 2);
            $table->date('VENCIMENTO');
            $table->integer('PARCELAS');
            $table->string('ID_HEX',255)->default('#FFFFFF');
            $table->string('STATUS', 20);
            $table->char('RECRIAR',3)->default('Nao');
            $table->char('DUMP')->default(' ');
            $table->dateTime('CRIADO_EM');
            $table->date('PAGO_EM')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('bills');
    }
};
