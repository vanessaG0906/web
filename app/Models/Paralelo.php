<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paralelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
    ];

    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class);
    }
}
