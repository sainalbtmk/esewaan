<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Support\ListSearch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));

        $logs = ActivityLog::query()
            ->with(['user:id,name', 'performedBy:id,name'])
            ->when($search !== '', function ($query) use ($search): void {
                $like = ListSearch::like($search);
                $actionMatches = ListSearch::matchingKeys(ActivityLog::actionLabels(), $search);

                $query->where(function ($query) use ($like, $actionMatches): void {
                    $query->where('actor_name', 'like', $like)
                        ->orWhere('subject_name', 'like', $like)
                        ->orWhere('description', 'like', $like);

                    if ($actionMatches !== []) {
                        $query->orWhereIn('action', $actionMatches);
                    }
                });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('audit-log.index', [
            'logs' => $logs,
            'search' => $search,
        ]);
    }
}
