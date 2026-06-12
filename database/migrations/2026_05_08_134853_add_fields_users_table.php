<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->char('NOTIFICAR_GASTO', 1)->default('N');
            $table->string('TIPO_NOTIF_GASTO')->nullable();
            $table->char('NOTIFICAR_VENC', 1)->default('N');
            $table->integer('VENC_DIAS')->nullable();
            $table->date('EMAIL_GASTO')->default(Carbon::now()->toDateString())->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
