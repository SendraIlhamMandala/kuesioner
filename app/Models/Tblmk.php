<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblmk extends Model
{
    use HasFactory;
    protected $guarded = [];  

    protected $table = 'tblmk';
    public $timestamps = false;
}
