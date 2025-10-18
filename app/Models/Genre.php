<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Genre extends Model
{ 
       protected $fillable = [ 'genre_name','description'];
    use HasFactory;



    // One genre has many books
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
