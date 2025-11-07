<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;


class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'title',
        'author_id',
        'publisher',
        'published_year',
        'isbn',
        'location_id',
        'status_id'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
