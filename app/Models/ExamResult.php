<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
  protected $guarded = ['id'];
  
  public function user() {
    return $this->belongsTo(User::class);
  }
  public function exam() {
    return $this->belongsTo(Exam::class);
  }
  public function casts()
  {
    return [
      'jawaban' => 'array'
    ];
  }
}
