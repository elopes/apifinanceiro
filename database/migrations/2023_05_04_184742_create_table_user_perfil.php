<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Reference\Reference;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_perfil', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('user_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->string('tipo')->nullable(false);
            $table->string('cpf')->unique()->nullable();
            $table->string('cnpj')->unique()->nullable();
            $table->timestamps();
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_perfil');
    }
};
