<?php

namespace App\Models;

use App\Models\User;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
  public function user() {
    return $this->belongsTo(User::class)
  }
  public function exam() {
    return $this->belongsTo(Exam::class)
  }
}
