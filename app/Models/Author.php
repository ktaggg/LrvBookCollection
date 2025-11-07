<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function ratings()
    {
        return $this->hasManyThrough(Rating::class, Book::class);
    }
}
