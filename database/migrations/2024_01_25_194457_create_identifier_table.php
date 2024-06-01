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
        Schema::create('identifier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_USR');
            $table->foreign('ID_USR')->references('id')->on('users')->onUpdate('cascade');
            $table->string('IDENTIF',40);
            $table->string('DESCRICAO', 255);
            $table->string('ID_HEX',255)->default('#FFFFFF');
            $table->char('ATIVO', 3)->default('Sim');
            $table->char('DUMP')->default(' ');
            $table->timestamps();
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
