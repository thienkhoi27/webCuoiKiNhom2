<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class editExpenseController extends Controller
{
    public function editExpense($id, Request $request)
    {
        if (!session('username')) {
            return redirect('/login');
        }

        $transaction = Transaction::where('id', $id)
            ->where('user', session('username'))
            ->firstOrFail();

        $data = $request->validate([
            'expense' => ['required', 'string', 'max:255'],
            'total'   => ['required', 'integer', 'min:0'],
            'date'    => ['required', 'date'],
        ]);

        $transaction->update([
            'expense' => $data['expense'],
            'total' => (int)$data['total'],
            'date' => $data['date'],
        ]);

        return redirect('/')->with('success', 'Expense updated successfully!');
    }
}
