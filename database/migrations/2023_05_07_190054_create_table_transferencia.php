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
        Schema::create('transferencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('origem_id')->nullable(false);
            $table->string('transferido_de')->nullable(false);
            $table->unsignedBigInteger('destinatario_id')->nullable(false);
            $table->string('recebido_por')->nullable(false);
            $table->decimal('quantia', 10, 2)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_transferencia');
    }
};
