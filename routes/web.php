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

    // Nên orderBy desc để "gần nhất" đúng nghĩa (tuỳ bạn)
    $transactions = Transaction::where('user', $user)
        ->orderBy('date', 'desc')
        ->get();

    $monthStart = date('Y-m-01');
    $monthEnd   = date('Y-m-t');

    $spentThisMonth = Transaction::where('user', $user)
        ->where('type', 'expense')
        ->whereBetween('date', [$monthStart, $monthEnd])
        ->sum('total');

    $incomeThisMonth = Transaction::where('user', $user)
        ->where('type', 'income')
        ->whereBetween('date', [$monthStart, $monthEnd])
        ->sum('total');

    // Data chart theo ngày trong tháng
    $dailyExpense = Transaction::where('user', $user)->where('type', 'expense')
        ->whereBetween('date', [$monthStart, $monthEnd])
        ->selectRaw('date, SUM(total) as total')
        ->groupBy('date')->orderBy('date')->get()->keyBy('date');

    $dailyIncome = Transaction::where('user', $user)->where('type', 'income')
        ->whereBetween('date', [$monthStart, $monthEnd])
        ->selectRaw('date, SUM(total) as total')
        ->groupBy('date')->orderBy('date')->get()->keyBy('date');

    $expensePoints = [];
    $incomePoints  = [];

    for ($d = 1; $d <= (int)date('t'); $d++) {
        $date = date('Y-m-') . str_pad((string)$d, 2, '0', STR_PAD_LEFT);
        $x = strtotime($date) * 1000;

        $expensePoints[] = ["x" => $x, "y" => (int)($dailyExpense[$date]->total ?? 0)];
        $incomePoints[]  = ["x" => $x, "y" => (int)($dailyIncome[$date]->total ?? 0)];
    }

    $monthKey = now()->startOfMonth()->toDateString(); // YYYY-mm-01

    $categories = Category::where('user', $user)->get()->map(function ($c) use ($user, $monthStart, $monthEnd, $monthKey) {
        // Chỉ tính CHI theo danh mục
        $spent = Transaction::where('user', $user)
            ->where('type', 'expense')
            ->where('category_id', $c->id)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('total');

        $budget = CategoryBudget::where('category_id', $c->id)
            ->where('month', $monthKey)
            ->value('amount') ?? 0;

        $spent  = (int)$spent;
        $budget = (int)$budget;

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

        $c->spent   = $spent;
        $c->budget  = $budget;
        $c->status  = $status;
        $c->variant = $variant;
        $c->percent = min(100, max(0, $percent));

        return $c;
    });

    return view('dashboard', [
        'page' => 'Bảng điều khiển',
        'transactions' => $transactions,
        'categories' => $categories,

        // THÊM 4 BIẾN NÀY
        'spentThisMonth' => (int)$spentThisMonth,
        'incomeThisMonth' => (int)$incomeThisMonth,
        'expensePoints' => $expensePoints,
        'incomePoints' => $incomePoints,
    ]);
})->name('dashboard');


Route::get('/add-expense', function () {
    if (session('username') == null) return redirect('/login');

    $user = session('username');

    $data = [
        'page' => 'Thêm chi/thu',
        'categories' => Category::where('user', $user)->orderBy('name')->get(),
    ];

    return view('add-expense', $data);
});

Route::get('expense/{id}', function ($id) {
    if (session('username') == null) return redirect('/login');

    $user = session('username');

    $data = [
        'page' => 'Chỉnh sửa chi phí',
        'transactions' => Transaction::findOrFail($id),
        'categories' => Category::where('user', $user)->orderBy('name')->get(),
    ];

    return view('expense', $data);
});


Route::post('/edit-expense/{id}', [editExpenseController::class, 'editExpense'])->name('transactions.editExpense');

Route::get('delete-expense/{id}', function ($id) {
    Transaction::find($id)->delete();
    return redirect('/')->with('success', 'Expense deleted successfully!');
});

Route::post('/transactions', [addExpenseController::class, 'store'])->name('transactions.store');

Route::get('/analytics', function () {
    if (session('username') == null) return redirect('/login');

    $user = session('username');

    $transactions = Transaction::where('user', $user)
        ->orderBy('date', 'desc')
        ->get();

    $categoriesById = Category::where('user', $user)->get()->keyBy('id');

    return view('analytics', [
        'page' => 'Phân tích',
        'transactions' => $transactions,
        'categoriesById' => $categoriesById,
    ]);
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
