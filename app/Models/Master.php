<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Master extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'location',
        'file',
        'description'
    ];

    public function badminton()
    {
        return $this->hasMany(Badminton::class);
    }

    public function football()
    {
        return $this->hasMany(Football::class);
    }

    public function swimming()
    {
        return $this->hasMany(Swimming::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        if($filters['search'] ?? false){
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
    }
}
