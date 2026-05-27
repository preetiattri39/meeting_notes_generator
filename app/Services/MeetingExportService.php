<?php

namespace App\Services;

use App\Models\Meeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MeetingExportService
{
    public function downloadPdf(Meeting $meeting): Response
    {
        $pdf = Pdf::loadView('meetings.export', ['meeting' => $meeting->load(['actionItems', 'decisions', 'highlights', 'tags'])]);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$meeting->slug.'.pdf"',
        ]);
    }

    public function downloadDocx(Meeting $meeting): StreamedResponse
    {
        $meeting->load(['actionItems', 'decisions', 'highlights', 'tags']);

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTitle($meeting->title, 1);
        $section->addText("Category: {$meeting->category}");
        $section->addText("Status: {$meeting->status}");
        $section->addTextBreak();
        $section->addText(strip_tags($meeting->summary_markdown ?? 'No summary generated.'));
        $section->addTextBreak();
        $section->addTitle('Action Items', 2);

        foreach ($meeting->actionItems as $item) {
            $section->addListItem($item->description);
        }

        $section->addTitle('Decisions', 2);

        foreach ($meeting->decisions as $decision) {
            $section->addListItem($decision->decision);
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'meeting-docx');
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()->streamDownload(function () use ($tempPath) {
            readfile($tempPath);
            @unlink($tempPath);
        }, $meeting->slug.'.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
}
