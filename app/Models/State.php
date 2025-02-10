<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class State extends Model
{
    /** @use HasFactory<\Database\Factories\StateFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Scope to filter by archived (soft-deleted) records.
     */
    public function scopeArchived(Builder $query, $archived): void
    {
        if ($archived === "true") {
            $query->onlyTrashed(); // Fetch only soft-deleted records
        }
    }

    /**
     * Scope to search by state name or country name.
     */
    public function scopeSearch(Builder $query, $search): void
    {
        if (!empty($search)) {
            $query->where('states.name', 'like', '%' . $search . '%') // Search by state name
                ->orWhereHas('country', function ($q) use ($search) { // Search by country name
                    $q->where('countries.name', 'like', '%' . $search . '%');
                });
        }
    }

    /**
     * Scope to sort by a column and direction.
     */
    public function scopeSort(Builder $query, $sortColumn, $sortDirection): void
    {
        if ($sortColumn === 'country_id') {
            $query->join('countries', 'states.country_id', '=', 'countries.id')
                ->select('states.*', 'countries.name as country_name')
                ->orderBy('countries.name', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }
    }
}
