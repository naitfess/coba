<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

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
}
