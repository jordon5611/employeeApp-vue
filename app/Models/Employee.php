<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;
    use SoftDeletes;

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function scopeSearch(Builder $query, $search): void
    {
        if (!empty($search)) {
            $query->where('firstname', 'like', '%' . $search . '%')
                ->orWhere('lastname', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%') // Adjust fields as needed
                ->orWhere('username', 'like', '%' . $search . '%')
                ->orWhereHas('country', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('state', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('city', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }
    }


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
        if ($sortColumn === 'country_id') {
            $query->join('countries', 'employees.country_id', '=', 'countries.id')
                ->select('employees.*', 'countries.name as country_name')
                ->orderBy('countries.name', $sortDirection);
        } elseif ($sortColumn === 'state_id') {
            $query->join('states', 'employees.state_id', '=', 'states.id')
                ->select('employees.*', 'states.name as state_name')
                ->orderBy('states.name', $sortDirection);
        } elseif ($sortColumn === 'city_id') {
            $query->join('cities', 'employees.city_id', '=', 'cities.id')
                ->select('employees.*', 'cities.name as city_name')
                ->orderBy('cities.name', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }
    }

}
