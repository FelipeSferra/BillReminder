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
        Schema::dropIfExists('debt');
        Schema::create('debt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_USR');
            $table->foreign('ID_USR')->references('id')->on('users')->onUpdate('cascade');
            $table->string('NOME', 30);
            $table->string('EMAIL', 255)->nullable()->default(' ');
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
        Schema::dropIfExists('debt');
    }
};
