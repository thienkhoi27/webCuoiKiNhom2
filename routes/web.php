<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\addUserController;
use App\Http\Controllers\addExpenseController;
use App\Http\Controllers\editExpenseController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\settingsController;

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', [loginController::class, 'login']);

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [addUserController::class, 'register']);

Route::get('/', function () {
    $user = session('username');
    $data = ['page' => 'Bảng điều khiển', 'transactions' => Transaction::where('user', $user)->orderBy('date', 'asc')->get()];

    if (session('username') == null) {
        return redirect('/login');
    }

    return view('dashboard', $data);
})->name('dashboard');

Route::get('/add-expense', function () {
    $data = ['page' => 'Thêm chi phí'];

    if (session('username') == null) {
        return redirect('/login');
    }

    return view('add-expense', $data);
});

Route::get('expense/{id}', function ($id) {
    $data = ['page' => 'Chỉnh sửa chi phí', 'transactions' => Transaction::find($id)];

    if (session('username') == null) {
        return redirect('/login');
    }

    return view('expense', $data);
});

Route::post('/edit-expense/{id}', [editExpenseController::class, 'editExpense'])->name('transactions.editExpense');

Route::get('delete-expense/{id}', function ($id) {
    Transaction::find($id)->delete();
    return redirect('/')->with('success', 'Expense deleted successfully!');
});

Route::post('/transactions', [addExpenseController::class, 'store'])->name('transactions.store');

Route::get('/analytics', function () {
    $user = session('username');
    $data = ['page' => 'Phân tích', 'transactions' => Transaction::where('user', $user)->orderBy('date', 'asc')->get()];

    if (session('username') == null) {
        return redirect('/login');
    }

    return view('analytics', $data);
});

Route::get('/pdf', [PDFController::class, 'generatePDF']);

Route::get('/search', [PostController::class, 'search']);

Route::get('/reports', function () {
    $data = ['page' => 'Báo cáo'];

    if (session('username') == null) {
        return redirect('/login');
    }

    return view('reports', $data);
});

Route::get('/settings', function () {
    $data = ['page' => 'Cài đặt'];

    if (session('username') == null) {
        return redirect('/login');
    }

    return view('settings', $data);
});

Route::post('/settings', [settingsController::class, 'update']);

Route::get('/logout', function () {
    session()->forget('username');
    session()->forget('profilePicture');
    session()->flush();
    return redirect('/login')->with('success', 'You have been logged out!');
});