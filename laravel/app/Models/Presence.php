<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Presence extends Model
{
    protected $guarded = ['id'];
    
    public function user() {
      return $this->belongsTo(User::class);
    }
    public function scopePeriod($query, $date, $date1) {
      return $query->whereYear('created_at', $date->year)->whereMonth('created_at', $date1->month);
    }
}
