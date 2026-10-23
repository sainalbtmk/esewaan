<?php

namespace App\Models;

use App\Support\ContractExpiryReminderTier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractExpiryReminderSend extends Model
{
    /** @use HasFactory<\Database\Factories\ContractExpiryReminderSendFactory> */
    use HasFactory;

    protected $table = 'penghantaran_peringatan_kontrak';

    protected $fillable = [
        'rental_contract_id',
        'tier',
        'sent_on',
    ];

    protected function casts(): array
    {
        return [
            'tier' => ContractExpiryReminderTier::class,
            'sent_on' => 'date',
        ];
    }

    /**
     * @return BelongsTo<RentalContract, $this>
     */
    public function rentalContract(): BelongsTo
    {
        return $this->belongsTo(RentalContract::class, 'rental_contract_id');
    }
}
