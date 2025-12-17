<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class addExpenseController extends Controller
{
    public function store(Request $request)
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

        Transaction::create([
            'user' => session('username'),
            'type' => $type,
            'expense' => $request->expense, // mô tả
            'total' => $request->total,
            'date' => $request->date,
            'category_id' => ($type === 'expense') ? $request->category_id : null,
        ]);

        return redirect('/')->with('success', $type === 'income' ? 'Income added successfully!' : 'Expense added successfully!');
    }

}
