<?php

namespace App\Models\Builders;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

/**
 * @extends Builder<ActivityLog>
 */
class ActivityLogBuilder extends Builder
{
    /**
     * @param  array<string, mixed>  $values
     */
    public function update(array $values): int
    {
        throw new RuntimeException(ActivityLog::IMMUTABLE_MESSAGE);
    }

    public function delete(): int
    {
        throw new RuntimeException(ActivityLog::IMMUTABLE_MESSAGE);
    }

    public function increment($column, $amount = 1, array $extra = []): int
    {
        throw new RuntimeException(ActivityLog::IMMUTABLE_MESSAGE);
    }

    public function decrement($column, $amount = 1, array $extra = []): int
    {
        throw new RuntimeException(ActivityLog::IMMUTABLE_MESSAGE);
    }
}
