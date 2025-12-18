<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF(Request $request)
    {
        $request->validate([
            'fromDate' => ['required', 'date'],
            'toDate'   => ['required', 'date', 'after_or_equal:fromDate'],
        ]);

        $user = session('username');

        // Lấy transactions + tên danh mục (nếu có)
        $transactions = Transaction::query()
            ->where('transactions.user', $user)
            ->whereBetween('transactions.date', [$request->fromDate, $request->toDate])
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('transactions.*', 'categories.name as category_name')
            ->orderBy('transactions.date', 'asc')
            ->orderBy('transactions.id', 'asc')
            ->get();

        $totalExpense = (int) $transactions->where('type', 'expense')->sum('total');
        $totalIncome  = (int) $transactions->where('type', 'income')->sum('total');
        $net          = $totalIncome - $totalExpense;

        $data = [
            'user'         => $user,
            'fromDate'     => $request->fromDate,
            'toDate'       => $request->toDate,
            'printedAt'    => now()->format('Y-m-d H:i'),
            'transactions' => $transactions,
            'totalExpense' => $totalExpense,
            'totalIncome'  => $totalIncome,
            'net'          => $net,
        ];

        $pdf = Pdf::loadView('pdf.document', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('report.pdf');
    }
}
