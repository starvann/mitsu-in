<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Exam;

class Question extends Model
{
    protected $guarded = ['id'];
    
    public function exam() {
      return $this->belongsTo(Exam::class);
    }

    protected function casts(): array {
        return [
            'jawaban' => 'array',
            'jwbn_yg_benar' => 'integer'
        ];
    }
}
