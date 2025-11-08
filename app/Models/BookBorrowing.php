<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The BookBorrowing model handles the book lending records.
 * It tracks when books are borrowed, their due dates, and return status.
 */
class BookBorrowing extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'book_borrowings';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',           // The user who borrowed the book
        'book_id',           // The book that was borrowed
        'borrowed_date',     // When the book was borrowed
        'due_date',         // When the book should be returned
        'returned_date',    // When the book was actually returned (null if not returned)
        'extensions_count'  // Number of times the borrowing period was extended
    ];

    protected $casts = [
        'borrowed_date' => 'datetime',
        'due_date' => 'datetime',
        'returned_date' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
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