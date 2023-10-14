<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'msmhs';
    public $timestamps = false;

    protected $primaryKey = 'nimhs';

    public function kuesioners(): BelongsToMany
    {
        return $this->belongsToMany(Kuesioner::class, 'trkuesl', 'nimhs', 'klkues');
    }
}
