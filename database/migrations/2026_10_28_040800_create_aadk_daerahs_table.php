<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aadk_daerah', function (Blueprint $table) {
            $table->id();
            $table->string('negeri', 100);
            $table->string('daerah', 100);
            $table->string('nama_pejabat');
            $table->text('alamat')->nullable();
            $table->string('ketua_daerah')->nullable();
            $table->string('telefon', 100)->nullable();
            $table->string('faks', 100)->nullable();
            $table->string('emel');
            $table->unsignedInteger('susunan');
            $table->timestamps();

            $table->unique(['negeri', 'daerah']);
            $table->index('negeri');
        });

        $now = now();
        $rows = array_map(
            fn (array $row): array => [
                ...$row,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            require database_path('data/aadk_daerah.php'),
        );

        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('aadk_daerah')->insert($chunk);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('aadk_daerah');
    }
};
