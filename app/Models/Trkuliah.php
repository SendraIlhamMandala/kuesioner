<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trkuliah extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'trkuliah';
    public $timestamps = false;


    public function trakd(): BelongsTo
    {
        // return $this->belongsTo(Trakd::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Trakd::class, 'kelas', 'kelas')->where('kdkmk', $this->kdkmk);
    }



    public function trkuesks(): HasMany
    {
        return $this->hasMany(Trkuesk::class, 'kdkmk', 'kdkmk')->where('nimhs', $this->nimhs)->where('thsms', $this->thsms);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class , 'nimhs' , 'nimhs');
    }
}
