<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahunsemester extends Model
{
    use HasFactory;
    //guarded
    protected $guarded = [];  

    public function hasils()
    {
        return $this->hasMany(Hasil::class);
    }

}
