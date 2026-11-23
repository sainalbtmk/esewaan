<?php

namespace App\Models;

use App\Models\Builders\ActivityLogBuilder;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

#[UseEloquentBuilder(ActivityLogBuilder::class)]
class ActivityLog extends Model
{
    public const IMMUTABLE_MESSAGE = 'Rekod log audit tidak boleh dikemaskini atau dipadam.';

    protected $table = 'log_aktiviti';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'performed_by_user_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }

    protected static function booted(): void
    {
        static::creating(function (ActivityLog $log): void {
            $actorId = $log->performed_by_user_id ?? $log->user_id;
            $log->actor_name = self::rememberedName($actorId);
            $log->subject_name = self::rememberedName($log->user_id);
        });
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new RuntimeException(self::IMMUTABLE_MESSAGE);
        }

        return parent::save($options);
    }

    public function delete(): bool
    {
        throw new RuntimeException(self::IMMUTABLE_MESSAGE);
    }

    public function displayName(): string
    {
        if (filled($this->actor_name)) {
            return $this->actor_name;
        }

        return $this->performedBy?->name
            ?? $this->user?->name
            ?? 'Pengguna tidak diketahui';
    }

    public function affectsAnotherUser(): bool
    {
        return $this->performed_by_user_id !== null
            && (int) $this->user_id !== (int) $this->performed_by_user_id;
    }

    public function activityLabel(): string
    {
        return self::labelFor($this->action);
    }

    public static function labelFor(string $action): string
    {
        return self::actionLabels()[$action] ?? ucfirst(str_replace('_', ' ', $action));
    }

    /**
     * @return array<string, string>
     */
    public static function actionLabels(): array
    {
        return [
            'login' => 'Log masuk',
            'login_failed' => 'Log masuk gagal',
            'login_blocked' => 'Log masuk disekat',
            'logout' => 'Log keluar',
            'profile_updated' => 'Kemaskini profil',
            'password_reset' => 'Reset kata laluan',
            'password_reset_requested' => 'Permintaan set semula kata laluan',
            'role_updated' => 'Ubah peranan',
            'account_activated' => 'Aktifkan akaun',
            'account_deactivated' => 'Nyahaktif akaun',
            'user_created' => 'Akaun didaftarkan',
            'two_factor_confirmed' => 'Pengesahan dua faktor diaktifkan',
            'emel_jawatan_updated' => 'Kemaskini emel jawatan',
            'application_submitted' => 'Hantar permohonan',
            'application_drafted' => 'Draf permohonan disimpan',
            'application_updated' => 'Permohonan dikemaskini',
            'application_sent_to_hq' => 'Permohonan dihantar ke Ibu Pejabat',
            'application_approved_by_hq' => 'Permohonan diluluskan Ibu Pejabat',
            'application_deleted' => 'Permohonan dipadam',
            'application_withdrawal_requested' => 'Permohonan tarik semula',
            'application_withdrawal_approved' => 'Tarik semula diluluskan',
            'application_withdrawal_rejected' => 'Tarik semula ditolak',
            'document_uploaded' => 'Muat naik dokumen',
            'draft_agreement_uploaded' => 'Muat naik draf perjanjian',
            'draft_agreement_completed' => 'Draf perjanjian selesai',
            'draft_agreement_returned_to_hq' => 'Draf dikembalikan ke Ibu Pejabat',
            'puu_review_approved' => 'Semakan PUU diluluskan',
            'puu_review_rejected' => 'Semakan PUU ditolak',
            'agreement_sent_for_stamp_duty' => 'Perjanjian dihantar untuk duti setem',
            'stamp_duty_sent_to_hq' => 'Duti setem dihantar ke Ibu Pejabat',
            'agreement_finalized' => 'Perjanjian dimuktamadkan',
            'contract_status_updated' => 'Kemaskini status kontrak',
            'contract_superseded' => 'Kontrak digantikan',
            'admin_proceed_step_completed' => 'Langkah tindakan disimpan',
            'follow_up_application_drafted' => 'Permohonan susulan dicipta',
            'follow_up_application_reverted' => 'Permohonan susulan dibatalkan',
            'report_exported' => 'Laporan dimuat turun',
        ];
    }

    private static function rememberedName(mixed $userId): string
    {
        $name = User::query()->whereKey($userId)->value('name');

        return is_string($name) && $name !== '' ? $name : 'Pengguna tidak diketahui';
    }
}
