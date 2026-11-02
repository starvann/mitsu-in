<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable;

  protected $guarded = [
    'id',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  public function presences() {
    return $this->hasMany(Presence::class);
  }

  public function userDetail() {
    return $this->hasOne(UserDetail::class);
  }

  public function examResults() {
    return $this->hasMany(ExamResult::class);
  }

  public function groups() {
    return $this->belongsToMany(Group::class, 'member_groups')->withTimestamps();
  }

  public function paymentProof() {
    return $this->hasOne(PaymentProof::class);
  }

  public function umur(): Attribute {
    return Attribute::make(get: fn() => $this->tanggal_lahir?->age);
  }

  protected function casts(): array
  {
    return [
      'password' => 'hashed',
      'tanggal_lahir' => 'date',
    ];
  }
}
