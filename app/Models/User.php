<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nmmhs',
        'email',
        'password',
        'nimhs',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //has many comments

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //has many hasils
    public function hasils()
    {
        return $this->hasMany(Hasil::class);
    }

    //has many trkuliah
    public function trkuliahs() :HasMany
    {
        return $this->hasMany(Trkuliah::class , 'nimhs' , 'nimhs');
    }

public function trkuesks(): HasMany
{
    return $this->hasMany(Trkuesk::class, 'nimhs', 'nimhs')
                ->where('thsms', Tahunsemester::where('status', 'aktif')->first()->thsms)
                ->whereIn('kdkmk', $this->trkuliahs->pluck('kdkmk'));
}

}
