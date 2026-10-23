<?php

namespace App\Models;

use App\Mail\PasswordResetMail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'negeri',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function hasConfirmedTwoFactor(): bool
    {
        return $this->two_factor_confirmed_at !== null;
    }

    public function consumeRecoveryCode(string $code): bool
    {
        $normalized = strtolower(trim($code));
        $codes = $this->two_factor_recovery_codes ?? [];

        foreach ($codes as $index => $hashed) {
            if (! is_string($hashed) || ! Hash::check($normalized, $hashed)) {
                continue;
            }

            unset($codes[$index]);
            $this->forceFill([
                'two_factor_recovery_codes' => array_values($codes),
            ])->save();

            return true;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin_hq', 'admin_negeri'], true);
    }

    public function isAdminHq(): bool
    {
        return $this->role === 'admin_hq';
    }

    public function isAdminNegeri(): bool
    {
        return $this->role === 'admin_negeri';
    }

    public function isActive(): bool
    {
        return (bool) ($this->is_active ?? true);
    }

    public function statusLabel(): string
    {
        return $this->isActive() ? 'Aktif' : 'Nyahaktif';
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'admin_hq' => 'Ibu Pejabat',
            'admin_negeri' => 'Negeri',
            default => 'Tidak diketahui',
        };
    }

    public function homeRoute(): string
    {
        return route('dashboard');
    }

    public function isVisibleToAdmin(User $admin): bool
    {
        if ($admin->isAdminHq()) {
            return true;
        }

        if ($admin->isAdminNegeri()) {
            return filled($admin->negeri)
                && $this->negeri === $admin->negeri
                && $this->role === 'admin_negeri';
        }

        return false;
    }

    /**
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeVisibleToAdmin(Builder $query, User $admin): Builder
    {
        if ($admin->isAdminNegeri() && filled($admin->negeri)) {
            return $query
                ->where('negeri', $admin->negeri)
                ->where('role', 'admin_negeri');
        }

        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<RentalContract, $this>
     */
    public function rentalContracts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RentalContract::class, 'submitted_by_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ActivityLog, $this>
     */
    public function activityLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        if (config('mail.block_password_reset_log')) {
            Log::error('password_reset_blocked_log_mailer', [
                'user_id' => $this->id,
            ]);

            return;
        }

        Mail::to($this->email)->send(new PasswordResetMail(
            user: $this,
            resetUrl: route('password.reset', [
                'token' => $token,
                'email' => $this->email,
            ]),
        ));
    }
}
