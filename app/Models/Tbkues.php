<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbkues extends Model
{
    use HasFactory;
    protected $guarded = [];  

    protected $table = 'tbkues';
    public $timestamps = false;
}
