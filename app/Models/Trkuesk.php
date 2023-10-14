<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trkuesk extends Model
{
    use HasFactory;
    protected $guarded = [];  

    protected $table = 'trkuesk';
    public $timestamps = false;
}
