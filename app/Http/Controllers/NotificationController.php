<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\SidebarNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request, SidebarNotificationService $service): View
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($user->isAdmin(), 403, 'Akses ditolak.');

        $items = $service->inboxItems($user);
        $counts = $service->forUser($user);

        return view('notifications.index', [
            'items' => $items,
            'inboxTotal' => $counts['inbox_total'],
        ]);
    }

    public function markAllAsRead(Request $request, SidebarNotificationService $service): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($user->isAdmin(), 403, 'Akses ditolak.');

        $marked = $service->markInboxAsViewed($user);

        if ($marked > 0) {
            ActivityLogger::log(
                $user,
                'notifications_marked_read',
                'Semua notifikasi ditanda sebagai dibaca.',
                ['count' => $marked],
                performedBy: $user,
            );
        }

        return redirect()
            ->route('notifications.index')
            ->with('success', $marked > 0
                ? 'Semua notifikasi telah ditanda sebagai dibaca.'
                : 'Tiada notifikasi untuk ditanda sebagai dibaca.');
    }
}
