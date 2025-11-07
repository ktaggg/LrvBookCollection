<?php

namespace App\Filters;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookFilter extends QueryFilter
{
    public function apply(array $filters)
    {
        foreach ($filters as $key => $value) {
            if (method_exists($this, $key) && !is_null($value)) {
                $this->$key($value);
            }
        }
        return $this->builder;
    }

    public function category($category)
    {
        return $this->builder->whereIn('category_id', $category);
    }

    public function author($author)
    {
        return $this->builder->where('author_id', $author);
    }

    public function yearFrom($year) {
        if (is_numeric($year)) {
            return $this->builder->where('year_published', '>=', (int) $year);
        }
        return $this->builder;
    }

    public function yearTo($year) {
        if (is_numeric($year)) {
            return $this->builder->where('year_published', '<=', (int) $year);
        }
        return $this->builder;
    }

    public function status($status) {
        return $this->builder->where('status_id', $status);
    }

    public function location($location) {
        return $this->builder->where('location_id', $location);
    }

    public function ratingMin($min) {
        return $this->builder->whereIn('id', function ($query) use ($min) {
            $query->select('book_id')
                  ->from('ratings')
                  ->groupBy('book_id')
                  ->havingRaw('AVG(rating) >= ?', [$min]);
        });
    }

    public function ratingMax($max) {
        return $this->builder->whereIn('id', function ( $query) use ($max) {
            $query->selectRaw('book_id')
                  ->from('ratings')
                  ->groupBy('book_id')
                  ->havingRaw('AVG(rating) <= ?', [$max]);
        });
    }

    public function search($keyword) {
        return $this->builder->where(function ($query) use ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%')
            ->orWhere('isbn', 'like', '%' . $keyword . '%')
            ->orWhere('publisher', 'like', '%' . $keyword . '%')
            ->orWhereHas('author', fn($query) => $query->where('name', 'like', '%' . $keyword . '%'));
        });
    }
}
