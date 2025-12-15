<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  protected $guarded = ['id'];

  public function memberGroups() {
    return $this->hasMany(MemberGroup::class);
  }
}
