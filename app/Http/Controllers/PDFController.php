<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Filament\Infolists\Infolist;

class PDFController extends Controller
{
    public function contractpdf($id)
    {
        $contract = Contract::with(['surveys', 'surveys.surveyors', 'surveys.assets'])
            ->where('id', $id)
            ->findOrFail($id);

        $users = User::all();

        $data = [
            'date' => $contract->selesai_kontrak,
            'users' => $users,
            'contract' => $contract
        ];

        $pdf = PDF::loadView('contractPDF', $data);

        return $pdf->stream('ContractDetails.pdf');
    }
}
