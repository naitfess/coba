<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Football extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'category_umur',
        'category_id',
        'master_id',
    ];

    protected $with = ['master', 'category'];

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        if($filters['search'] ?? false){
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
    }
}
