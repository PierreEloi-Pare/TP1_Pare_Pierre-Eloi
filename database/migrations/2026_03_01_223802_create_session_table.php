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
        //Lors de la remise 1, mes migrations n'étaient pas bien divisées, j'ai donc réusiné la sépération
        //des migrations en créant un fichier pour chaque migrations
         Schema::create('sessions', function (Blueprint $table) { // Illuminate lançait une erreur si cette table n'était pas là, probablement à cause d'une config
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};

