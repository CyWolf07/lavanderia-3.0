<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;

    protected $table = 'producciones';
    protected $fillable = ['user_id', 'prenda_id', 'cantidad', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prenda()
    {
        return $this->belongsTo(Prenda::class);
    }
}
