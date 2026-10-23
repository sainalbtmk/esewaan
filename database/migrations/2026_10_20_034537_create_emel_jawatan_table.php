<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emel_jawatan', function (Blueprint $table) {
            $table->id();
            $table->string('kod', 50)->unique();
            $table->string('nama_jawatan');
            $table->string('email');
            $table->unsignedInteger('susunan');
            $table->timestamps();
        });

        $now = now();

        DB::table('emel_jawatan')->insert([
            [
                'kod' => 'ketua_pengarah',
                'nama_jawatan' => 'Ketua Pengarah',
                'email' => 'ruslin@aadk.gov.my',
                'susunan' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kod' => 'tkpp',
                'nama_jawatan' => 'TKPP',
                'email' => 'shobah@aadk.gov.my',
                'susunan' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kod' => 'pkp',
                'nama_jawatan' => 'PKP',
                'email' => 'lai@aadk.gov.my',
                'susunan' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kod' => 'kpp',
                'nama_jawatan' => 'KPP',
                'email' => 'zasmi@aadk.gov.my',
                'susunan' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('emel_jawatan');
    }
};
