<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class AssignmentPDFController extends Controller
{
    public function assignmentPDF($id)
    {
        $assignment = Assignment::with(['contracts', 'contracts.surveyors', 'contracts.industries', 'contracts.contract_types'])
            ->where('id', $id)
            ->findOrFail($id);

        $data = [
            'date' => $assignment->tanggal_penugasan,
            'assignment' => $assignment
        ];

        $pdf = PDF::loadView('assignmentPDF', $data);

        return $pdf->stream('AssignmentLetter.pdf');
    }
}
