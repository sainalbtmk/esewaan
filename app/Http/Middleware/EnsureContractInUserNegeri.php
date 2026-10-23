<?php

namespace App\Http\Middleware;

use App\Models\RentalContract;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureContractInUserNegeri
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $contract = $request->route('contract');

        if ($user?->isAdminNegeri() && $contract instanceof RentalContract) {
            $contract->loadMissing('premise');

            if (! filled($user->negeri) || $contract->premise?->negeri !== $user->negeri) {
                abort(404);
            }
        }

        return $next($request);
    }
}
