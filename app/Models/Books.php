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
}
