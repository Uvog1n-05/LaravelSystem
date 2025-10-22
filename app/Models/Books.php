<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;

class Books extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'author', 'about', 'number_of_books', 'genre_id', 'user_id'];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
