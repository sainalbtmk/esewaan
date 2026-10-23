<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSessionGuard
{
    public static function invalidateOtherSessions(User $user): void
    {
        $user->forceFill([
            'remember_token' => Str::random(60),
        ])->save();

        if (config('session.driver') !== 'database') {
            return;
        }

        $query = DB::table((string) config('session.table', 'sessions'))
            ->where('user_id', $user->id);

        $currentId = session()->getId();

        if (auth()->id() === $user->id && is_string($currentId) && $currentId !== '') {
            $query->where('id', '!=', $currentId);
        }

        $query->delete();
    }
}
