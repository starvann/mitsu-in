<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use Notifiable;

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

  public function groups() {
    return $this->belongsToMany(Group::class, 'member_groups')->withTimestamps();
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
