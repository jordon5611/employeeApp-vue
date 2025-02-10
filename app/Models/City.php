<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

     /**
     * Scope for searching cities, states, and countries.
     */
    public function scopeSearch(Builder $query, $search): void
    {
        if (!empty($search)) {
            $query->where('cities.name', 'like', '%' . $search . '%')
                ->orWhereHas('state', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('country', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
        }
    }

    /**
     * Scope to include only soft-deleted records if requested.
     */
    public function scopeArchived(Builder $query, $archived): void
    {
        if ($archived === "true") {
            $query->onlyTrashed();
        }
    }

    /**
     * Scope for sorting cities based on column and direction.
     */
    public function scopeSort(Builder $query, $sortColumn, $sortDirection): void
    {
        if ($sortColumn === 'state_id') {
            $query->join('states', 'cities.state_id', '=', 'states.id')
                ->select('cities.*', 'states.name as state_name')
                ->orderBy('states.name', $sortDirection);
        } elseif ($sortColumn === 'country_id') {
            $query->join('states', 'cities.state_id', '=', 'states.id')
                ->join('countries', 'states.country_id', '=', 'countries.id')
                ->select('cities.*', 'countries.name as country_name')
                ->orderBy('countries.name', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }
    }
}
