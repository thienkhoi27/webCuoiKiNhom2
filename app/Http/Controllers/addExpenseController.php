<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class addExpenseController extends Controller
{
    public function store(Request $request)
    {
        // Create a new transaction record
        $data = $request->validate([
            'expense' => ['required','string','max:255'],
            'total' => ['required','integer','min:0'],
            'date' => ['required','date'],
            'category_id' => ['nullable','integer'],
        ]);

        Transaction::create([
            'user' => session('username'),
            'expense' => $data['expense'],
            'category_id' => $data['category_id'] ?? null,
            'total' => (int)$data['total'],
            'date' => $data['date'],
        ]);

        return redirect('/')->with('success', 'Expense added successfully!');
    }
}
