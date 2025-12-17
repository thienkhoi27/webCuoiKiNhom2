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
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\CategoryBudget;
// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::post('/categories/{id}/budget', [CategoryController::class, 'setBudget']);

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', [loginController::class, 'login']);

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [addUserController::class, 'register']);

Route::get('/', function () {
    if (session('username') == null) return redirect('/login');

    $user = session('username');

    $transactions = Transaction::where('user', $user)->orderBy('date', 'asc')->get();

    $monthStart = now()->startOfMonth()->toDateString();
    $monthEnd   = now()->endOfMonth()->toDateString();
    $monthKey   = now()->startOfMonth()->toDateString(); // YYYY-mm-01

    $categories = Category::where('user', $user)->get()->map(function ($c) use ($user, $monthStart, $monthEnd, $monthKey) {
        $spent = Transaction::where('user', $user)
            ->where('category_id', $c->id)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('total');

        $budget = CategoryBudget::where('category_id', $c->id)
            ->where('month', $monthKey)
            ->value('amount') ?? 0;

        $spent = (int)$spent;
        $budget = (int)$budget;

        // trạng thái
        if ($budget <= 0) {
            $status = 'Chưa đặt';
            $variant = 'blue';
            $percent = 0;
        } else {
            $percent = (int)round(($spent / $budget) * 100);
            if ($percent < 70) { $status = 'An toàn'; $variant = 'blue'; }
            elseif ($percent <= 100) { $status = 'Cảnh báo'; $variant = 'yellow'; }
            else { $status = 'Vượt hạn mức'; $variant = 'red'; }
        }

        $c->spent = $spent;
        $c->budget = $budget;
        $c->status = $status;
        $c->variant = $variant;
        $c->percent = min(100, max(0, $percent));

        return $c;
    });

    return view('dashboard', [
        'page' => 'Bảng điều khiển',
        'transactions' => $transactions,
        'categories' => $categories,
    ]);
})->name('dashboard');

Route::get('/add-expense', function () {
    if (session('username') == null) return redirect('/login');

    return view('add-expense', [
        'page' => 'Thêm chi phí',
        'categories' => Category::where('user', session('username'))->get(),
    ]);
});

Route::get('expense/{id}', function ($id) {
    if (session('username') == null) return redirect('/login');

    return view('expense', [
        'page' => 'Chỉnh sửa chi phí',
        'transactions' => Transaction::findOrFail($id),
        'categories' => Category::where('user', session('username'))->get(),
    ]);
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