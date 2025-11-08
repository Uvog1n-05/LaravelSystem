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
        return $this->hasMany(BookBorrowing::class)
            ->whereNull('returned_date')
            ->orderBy('due_date', 'asc');
    }

    // Get all books (including returned)
    public function allBorrowedBooks()
    {
        return $this->hasMany(BookBorrowing::class)
            ->orderBy('borrowed_date', 'desc');
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
