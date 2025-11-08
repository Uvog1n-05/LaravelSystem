<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;

/**
 * The Books model represents a book in the library system.
 * Each book can have multiple copies available for borrowing.
 */
class Books extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'title',        // The title of the book
        'author',       // The author of the book
        'about',        // Description or summary of the book
        'number_of_books', // Number of copies available
        'genre_id',     // Foreign key to the genres table
        'user_id',      // Foreign key to users table (admin who added the book)
        'cover_image'   // Filename of the book's cover image
    ];

    /**
     * Append the cover_image_url to the model's array form.
     * 
     * @var array
     */
    protected $appends = ['cover_image_url'];

    /**
     * Get the genre that this book belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * Get the user (admin) who added this book.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/books/' . $this->cover_image);
        }
        return asset('img/default-book-cover.jpg');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'book_user_favorites', 'book_id', 'user_id')
                    ->withTimestamps();
    }

    public function borrows()
    {
        return $this->hasMany(BookBorrowing::class, 'book_id');
    }

    public function borrowers()
    {
        return $this->hasManyThrough(
            User::class,
            BookBorrowing::class,
            'book_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function activeBorrowings()
    {
        return $this->borrows()->whereNull('returned_date');
    }

    public function getAvailableCopiesAttribute()
    {
        $borrowedCount = $this->activeBorrowings()->count();
        return $this->number_of_books - $borrowedCount;
    }

    public function isAvailable()
    {
        return $this->available_copies > 0;
    }
}
