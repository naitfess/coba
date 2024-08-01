<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'size',
        'user_id',
        'surat_id',
        'master_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function surat(){
        return $this->belongsTo(Surat::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        if($filters['search'] ?? false){
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
    }
}
