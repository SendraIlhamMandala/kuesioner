<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trkuesk extends Model
{
    use HasFactory;
    protected $guarded = [];  

    protected $table = 'trkuesk';
    public $timestamps = false;


    public function trkuliah(): BelongsTo
    {
        // return $this->belongsTo(Trakd::class, 'foreign_key', 'owner_key');
        // return $this->hasMany(Trkuesk::class, 'kdkmk', 'kdkmk')->where('nimhs', $this->nimhs);
        return $this->belongsTo(Trkuliah::class, 'kdkmk', 'kdkmk')->where('nimhs', $this->nimhs);

    }

}
