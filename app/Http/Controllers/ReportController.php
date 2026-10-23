<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateReportRequest;
use App\Services\HqReportService;
use App\Support\MalaysianStates;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(private HqReportService $reports) {}

    public function index(GenerateReportRequest $request): View
    {
        $filters = $request->filters();
        $report = $this->reports->build($filters['jenis'], $filters['negeri']);

        return view('reports.index', [
            'report' => $report,
            'negeriList' => MalaysianStates::all(),
            'jenisList' => HqReportService::typeLabels(),
            'filters' => $filters,
        ]);
    }

    public function exportPdf(GenerateReportRequest $request): Response|StreamedResponse
    {
        $filters = $request->filters();
        $report = $this->reports->build($filters['jenis'], $filters['negeri']);

        $filename = 'laporan-esewaan-'.now()->format('Ymd-His').'.pdf';
        $paper = in_array($report['jenis'], [
            HqReportService::TYPE_KONTRAK_NEGERI,
            HqReportService::TYPE_STATUS_SEWAAN,
        ], true) ? 'a3' : 'a4';

        return Pdf::loadView('reports.pdf', [
            'report' => $report,
        ])
            ->setPaper($paper, 'landscape')
            ->download($filename);
    }
}
