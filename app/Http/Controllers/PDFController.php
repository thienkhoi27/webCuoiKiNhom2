<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF(Request $request)
    {
        $user = session('username');
        $data = ['transactions' => Transaction::where('user', $user)->whereBetween('date', [$request->fromDate, $request->toDate])->orderBy('date', 'asc')->get()];
        $pdf = PDF::loadView('pdf.document', $data);
        return $pdf->stream('report.pdf');
    }
}
