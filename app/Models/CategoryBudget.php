<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBudget extends Model
{
    protected $fillable = ['category_id', 'month', 'amount'];

    protected $casts = [
        'month' => 'date',
        'amount' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
