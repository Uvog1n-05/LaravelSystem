<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ✅ Hash password automatically
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Relationship with borrowed books
    public function borrowedBooks()
    {
        return $this->belongsToMany(Books::class, 'book_user_borrowed', 'user_id', 'book_id')
                    ->withPivot('borrowed_date', 'due_date', 'returned_date')
                    ->withPivotValue('extensions_count', 0)
                    ->wherePivotNull('returned_date')
                    ->orderBy('pivot_due_date', 'asc')
                    ->using(BookBorrowing::class);
    }

    // Get all books (including returned)
    public function allBorrowedBooks()
    {
        return $this->belongsToMany(Books::class, 'book_user_borrowed', 'user_id', 'book_id')
                    ->withPivot('borrowed_date', 'due_date', 'returned_date')
                    ->orderBy('pivot_borrowed_date', 'desc');
    }

    // Relationship with Books
    public function books()
    {
        return $this->hasMany(Books::class, 'user_id');
    }

    // Relationship with favorite books
    public function favorites()
    {
        return $this->belongsToMany(Books::class, 'book_user_favorites', 'user_id', 'book_id')
                    ->withTimestamps();
    }
}
