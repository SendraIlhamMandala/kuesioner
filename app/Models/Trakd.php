<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trakd extends Model
{
    use HasFactory;
    protected $guarded = [];  

    protected $table = 'trakd';
    public $timestamps = false;

    
    public function trkuliahs() : HasMany
    {
        return $this->hasMany(Trkuliah::class, 'kelas', 'kelas')->where([
            'kdkmk' => $this->kdkmk,
            'thsms' => $this->thsms,
        ]);
    }

}
