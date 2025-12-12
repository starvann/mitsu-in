<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Presence;
use App\Models\ExamResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [
        'id',
        'stat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function presences() {
      return $this->hasMany(Presence::class);
    }
    
    public function examResults() {
      return $this->hasMany(ExamResult::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'pendidikan' => 'array',
            'struktur_keluarga' => 'array',
            'relasi_di_jepang' => 'array',
            'pengalaman' => 'array'
        ];
    }
}
