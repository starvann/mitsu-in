<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Presence extends Model
{
    protected $guard = ['id'];
    
    public function user() {
      return $this->belongsTo(User::class);
    }
}
