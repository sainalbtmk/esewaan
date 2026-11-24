<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrintAuditLogRequest;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Support\ClassifiedPdf;
use App\Support\ListSearch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'tarikhMula' => now()->timezone('Asia/Kuala_Lumpur')->toDateString(),
            'tarikhAkhir' => now()->timezone('Asia/Kuala_Lumpur')->toDateString(),
        ]);
    }

    public function exportPdf(PrintAuditLogRequest $request): Response|StreamedResponse
    {
        $start = $request->startsAt();
        $end = $request->endsAt();

        $logs = ActivityLog::query()
            ->with(['user:id,name', 'performedBy:id,name'])
            ->whereStartedBetween($start, $end)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $user = $request->user();

        if ($user instanceof User) {
            ActivityLogger::log(
                $user,
                'audit_log_printed',
                'Log audit dari '.$start->format('d/m/Y').' hingga '.$end->format('d/m/Y').' dicetak.',
                [
                    'tarikh_mula' => $start->toDateString(),
                    'tarikh_akhir' => $end->toDateString(),
                    'rekod' => $logs->count(),
                ],
                $user,
            );
        }

        $filename = 'log-audit-'.$start->format('Ymd').'-'.$end->format('Ymd').'.pdf';
        $generatedAt = Carbon::now('Asia/Kuala_Lumpur');
        $generatedBy = $user instanceof User ? $user->name : 'Pengguna tidak diketahui';

        return ClassifiedPdf::download(
            Pdf::loadView('audit-log.pdf', [
                'logs' => $logs,
                'tarikhMula' => $start,
                'tarikhAkhir' => $end,
                'generatedBy' => $generatedBy,
                'generatedAt' => $generatedAt,
            ])->setPaper('a4', 'landscape'),
            $filename,
            $generatedBy,
            $generatedAt,
        );
    }
}
