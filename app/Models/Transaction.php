<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /* use HasFactory; */  // Uncomment this if you need factory support
    protected $fillable = ['user', 'expense', 'total', 'date'];
    
    protected $casts = [
        'date' => 'date',
        'total' => 'integer',
    ];
}