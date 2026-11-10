<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('status_permohonan', function (Blueprint $table) {
            $table->json('hq_mati_setem_acknowledgements')->nullable()->after('negeri_mati_setem_acknowledgements');
        });
    }

    public function down(): void
    {
        Schema::table('status_permohonan', function (Blueprint $table) {
            $table->dropColumn('hq_mati_setem_acknowledgements');
        });
    }
};
