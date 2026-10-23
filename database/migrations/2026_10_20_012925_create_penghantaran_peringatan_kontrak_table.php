<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penghantaran_peringatan_kontrak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_contract_id')->constrained('status_permohonan')->cascadeOnDelete();
            $table->string('tier', 32);
            $table->date('sent_on');
            $table->timestamps();

            $table->unique(['rental_contract_id', 'tier', 'sent_on'], 'peringatan_kontrak_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penghantaran_peringatan_kontrak');
    }
};
