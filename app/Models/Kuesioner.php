<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kuesioner extends Model
{
    use HasFactory;
    protected $table = 'tbkues';
    public $timestamps = false;


    // public function mahasiswas(): BelongsToMany
    // {
    //     return $this->belongsToMany(Mahasiswa::class, 'trkuesl','klkues','nimhs');

    // }


}
