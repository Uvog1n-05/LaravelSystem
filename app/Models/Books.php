<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;

class Books extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'author', 'about', 'number_of_books', 'genre_id', 'user_id', 'cover_image'];

    protected $appends = ['cover_image_url'];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

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
        return $this->belongsToMany(User::class, 'book_user_borrowed', 'book_id', 'user_id')
                    ->withPivot('borrowed_date', 'due_date', 'returned_date', 'extensions_count')
                    ->using(BookBorrowing::class);
    }

    public function getAvailableCopiesAttribute()
    {
        $borrowedCount = $this->borrows()->whereNull('returned_date')->count();
        return $this->number_of_books - $borrowedCount;
    }

    public function isAvailable()
    {
        return $this->available_copies > 0;
    }
}
