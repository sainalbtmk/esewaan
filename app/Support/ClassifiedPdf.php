<?php

namespace App\Support;

use Barryvdh\DomPDF\PDF;
use Dompdf\Adapter\CPDF;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class ClassifiedPdf
{
    public static function download(PDF $pdf, string $filename, string $generatedBy, Carbon $generatedAt): Response
    {
        $generatedAt = $generatedAt->timezone('Asia/Kuala_Lumpur');
        $stamp = 'SULIT - Dijana oleh '.$generatedBy.' pada '.$generatedAt->format('d/m/Y H:i:s');

        $pdf->render();

        $canvas = $pdf->getDomPDF()->getCanvas();

        if ($canvas instanceof CPDF) {
            $canvas->page_script(function (int $pageNumber, int $pageCount, CPDF $canvas, $fontMetrics) use ($stamp): void {
                $bold = $fontMetrics->getFont('helvetica', 'bold');
                $regular = $fontMetrics->getFont('helvetica');
                $width = $canvas->get_width();
                $height = $canvas->get_height();

                $canvas->set_opacity(0.15);
                $canvas->text($width / 2 - 78, $height / 2, 'SULIT', $bold, 64, [0.35, 0.4, 0.45], 0, 0, -30);
                $canvas->set_opacity(1);

                $canvas->text(24, $height - 20, $stamp.' | Muka surat '.$pageNumber.'/'.$pageCount, $regular, 8, [0.2, 0.25, 0.32]);
            });
        }

        return $pdf->download($filename);
    }
}
