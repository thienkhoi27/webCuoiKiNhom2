<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user', 'expense', 'category_id', 'total', 'date'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}