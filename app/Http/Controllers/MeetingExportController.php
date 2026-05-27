<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Services\MeetingExportService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MeetingExportController extends Controller
{
    public function pdf(Meeting $meeting, MeetingExportService $exportService): Response
    {
        $this->authorize('view', $meeting);

        return $exportService->downloadPdf($meeting);
    }

    public function docx(Meeting $meeting, MeetingExportService $exportService): StreamedResponse
    {
        $this->authorize('view', $meeting);

        return $exportService->downloadDocx($meeting);
    }
}
