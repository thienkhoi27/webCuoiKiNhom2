<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class editExpenseController extends Controller
{
    public function editExpense($id, Request $request)
    {
        $type = $request->input('type', 'expense');

        if ($type === 'expense') {
            $request->validate([
                'expense' => 'required|string|max:255',
                'total' => 'required|integer|min:1',
                'date' => 'required|date',
                'category_id' => 'required|integer',
            ]);
        } else {
            $request->validate([
                'expense' => 'required|string|max:255',
                'total' => 'required|integer|min:1',
                'date' => 'required|date',
            ]);
        }

        Transaction::find($id)->update([
            'type' => $type,
            'expense' => $request->input('expense'),
            'total' => $request->input('total'),
            'date' => $request->input('date'),
            'category_id' => ($type === 'expense') ? $request->input('category_id') : null,
        ]);

        return redirect('/')->with('success', 'Transaction updated successfully!');
    }

}
