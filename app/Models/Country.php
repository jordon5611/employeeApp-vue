<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Country extends Model
{
    /** @use HasFactory<\Database\Factories\CountryFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];


    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Scope to filter by search query.
     */
    public function scopeSearch(Builder $query, $search): void
    {
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }
    }

    /**
     * Scope to sort results.
     */
    public function scopeSort(Builder $query, $sortColumn = 'id', $sortDirection = 'asc'): void
    {
        $query->orderBy($sortColumn, $sortDirection);
    }

    /**
     * Scope to fetch only archived (soft-deleted) records.
     */
    public function scopeArchived(Builder $query, $archived): void
    {
        if ($archived === "true") {
            $query->onlyTrashed();
        }
    }
}
