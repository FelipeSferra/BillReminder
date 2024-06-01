<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('EMAIL_SECUNDARIO')->nullable()->default('');
           // $table->dropColumn('email_verified_at');
            $table->char('NOTIFICAR_GASTO', 1)->default('N');
            $table->string('TIPO_NOTIF_GASTO')->nullable();
            $table->char('NOTIFICAR_VENC', 1)->default('N');
            $table->integer('VENC_DIAS')->nullable();
            $table->date('EMAIL_GASTO')->default(Carbon::now()->toDateString())->nullable();
            $table->char('DUMP')->default(' ');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
