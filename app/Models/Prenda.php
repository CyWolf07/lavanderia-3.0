<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prenda extends Model
{
    use HasFactory;

    protected $table = 'prendas';
    protected $fillable = ['nombre', 'tipo', 'precio'];

    public function producciones()
    {
        return $this->hasMany(Produccion::class);
    }
}
