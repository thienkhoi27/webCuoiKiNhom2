<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        if (!session('username')) return redirect('/login');

        $user = session('username');
        $month = now()->startOfMonth()->toDateString(); // YYYY-mm-01

        $categories = Category::where('user', $user)->get()->map(function ($c) use ($month) {
            $budget = CategoryBudget::where('category_id', $c->id)->where('month', $month)->value('amount') ?? 0;
            $c->budget_amount = (int)$budget;
            return $c;
        });

        return view('categories', [
            'page' => 'Danh mục',
            'categories' => $categories,
            'month' => $month,
        ]);
    }

    public function store(Request $request)
    {
        if (!session('username')) return redirect('/login');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'icon' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('categories', 'public');
        }

        Category::create([
            'user' => session('username'),
            'name' => $data['name'],
            'icon_path' => $iconPath,
        ]);

        return redirect('/categories')->with('success', 'Tạo danh mục thành công!');
    }

    public function setBudget($id, Request $request)
    {
        if (!session('username')) return redirect('/login');

        $category = Category::where('id', $id)
            ->where('user', session('username'))
            ->firstOrFail();

        $data = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'amount' => ['required', 'integer', 'min:0'],
        ]);

        $monthDate = $data['month'] . '-01';

        CategoryBudget::updateOrCreate(
            ['category_id' => $category->id, 'month' => $monthDate],
            ['amount' => (int)$data['amount']]
        );

        return redirect('/categories')->with('success', 'Cập nhật hạn mức thành công!');
    }
}
