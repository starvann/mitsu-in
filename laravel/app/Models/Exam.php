<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Exam extends Model
{
    protected $guarded = ['id'];
    public function questions() {
      return $this->hasMany(Question::class);
    }
    
}
