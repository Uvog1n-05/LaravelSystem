<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Books;

/**
 * The Genre model represents a book category in the library system.
 * Each book must belong to a genre, and a genre can have multiple books.
 */
class Genre extends Model
{ 
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name',         // The name of the genre
        'description'   // A description of what this genre represents
    ];



    /**
     * Get all books belonging to this genre.
     * One genre can have many books.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books()
    {
        return $this->hasMany(Books::class);
    }
}
