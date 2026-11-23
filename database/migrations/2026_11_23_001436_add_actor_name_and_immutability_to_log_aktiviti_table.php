<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('log_aktiviti', function (Blueprint $table) {
            $table->string('actor_name')->nullable()->after('performed_by_user_id');
            $table->string('subject_name')->nullable()->after('actor_name');
            $table->index('created_at');
            $table->index('actor_name');
        });

        DB::statement(<<<'SQL'
            UPDATE log_aktiviti
            SET actor_name = (
                SELECT name FROM users
                WHERE users.id = COALESCE(log_aktiviti.performed_by_user_id, log_aktiviti.user_id)
            ),
            subject_name = (
                SELECT name FROM users
                WHERE users.id = log_aktiviti.user_id
            )
            WHERE actor_name IS NULL OR subject_name IS NULL
        SQL);

        $this->installImmutabilityTriggers();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropImmutabilityTriggers();

        Schema::table('log_aktiviti', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['actor_name']);
            $table->dropColumn(['actor_name', 'subject_name']);
        });
    }

    private function installImmutabilityTriggers(): void
    {
        $message = 'Rekod log audit tidak boleh dikemaskini atau dipadam.';
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::unprepared("CREATE TRIGGER log_aktiviti_block_update BEFORE UPDATE ON log_aktiviti FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '{$message}'");
            DB::unprepared("CREATE TRIGGER log_aktiviti_block_delete BEFORE DELETE ON log_aktiviti FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '{$message}'");

            return;
        }

        if ($driver !== 'sqlite') {
            throw new RuntimeException('Pencetus log audit tidak disokong untuk pemacu '.$driver.'.');
        }

        DB::unprepared("CREATE TRIGGER log_aktiviti_block_update BEFORE UPDATE ON log_aktiviti BEGIN SELECT RAISE(ABORT, '{$message}'); END");
        DB::unprepared("CREATE TRIGGER log_aktiviti_block_delete BEFORE DELETE ON log_aktiviti BEGIN SELECT RAISE(ABORT, '{$message}'); END");
    }

    private function dropImmutabilityTriggers(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS log_aktiviti_block_update');
        DB::unprepared('DROP TRIGGER IF EXISTS log_aktiviti_block_delete');
    }
};
