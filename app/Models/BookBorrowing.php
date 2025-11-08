<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;

class BookBorrowing extends Pivot
{
    protected $table = 'book_user_borrowed';

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_date',
        'due_date',
        'returned_date',
        'extensions_count'
    ];

    protected $casts = [
        'borrowed_date' => 'datetime',
        'due_date' => 'datetime',
        'returned_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Books::class);
    }

    // Check if the book is overdue
    public function isOverdue()
    {
        return !$this->returned_date && now()->greaterThan($this->due_date);
    }

    // Get remaining days
    public function getRemainingDays()
    {
        if ($this->returned_date) {
            return 0;
        }
        return now()->diffInDays($this->due_date, false);
    }
}