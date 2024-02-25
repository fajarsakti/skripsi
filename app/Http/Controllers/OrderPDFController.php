<?php

namespace App\Http\Controllers;

use App\Mail\OrderSent;
use App\Models\Contract;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Mail;

class OrderPDFController extends Controller
{
    public function contractpdf($id)
    {
        $contract = Contract::with(['surveys', 'surveys.surveyors', 'surveys.assets', 'surveys.assignments'])
            ->where('id', $id)
            ->findOrFail($id);

        $user = User::where('role', 'debitur');

        $data = [
            'date' => $contract->selesai_kontrak,
            'users' => $user,
            'contract' => $contract,
        ];

        // dd($data);

        $pdf = PDF::loadView('contractPDF', $data);

        set_time_limit(300);

        return $pdf->output();
    }
}
