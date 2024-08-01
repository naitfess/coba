<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class);
    }
}
