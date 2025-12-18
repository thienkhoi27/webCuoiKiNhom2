<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;

class addExpenseController extends Controller
{
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();

        if ($data['type'] === 'income') {
            $data['category_id'] = null;
        }

        Transaction::create([
            'user' => session('username'),
            'type' => $data['type'],
            'expense' => $data['expense'],
            'total' => (int)$data['total'],
            'date' => $data['date'],
            'category_id' => $data['category_id'] ?? null,
        ]);

        return redirect('/')->with(
            'success',
            $data['type'] === 'income' ? 'Thu nhập đã được thêm!' : 'Chi phí đã được thêm!'
        );
    }
}
