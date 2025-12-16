<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::where('expense', 'like', '%' . $request->search . '%')->orWhere('date', 'like', '%' . $request->search . '%')->orWhere('date', 'like', '%' . date('m', strtotime($request->search)) . '%')->orWhere('total', 'like', '%' . $request->search . '%')->orWhere('description', 'like', '%' . $request->search . '%')->get();
            return response()->json($data);
        }

        // Optional: handle non-AJAX requests if needed
        return view('your-view', [
            'transactions' => Transaction::all() // or any default view
        ]);
    }
}