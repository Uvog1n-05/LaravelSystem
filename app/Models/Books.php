<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;

class Books extends Model
{
    protected $fillable = ['title', 'author', 'about', 'number_of_books','genre_id'];
    use HasFactory;

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
