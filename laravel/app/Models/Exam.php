<?php

namespace App\Models;

use App\Models\Question;
use App\Models\ExamResult;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = ['id'];
    
    public function questions() {
      return $this->hasMany(Question::class);
    }
    
    public function examResults() {
      return $this->hasMany(ExamResult::class);
    }
}
