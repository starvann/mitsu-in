<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Exam extends Model
{
    protected $guard = ['id'];
    public function question() {
      return $this->hasMany(Question::class);
    }
}
