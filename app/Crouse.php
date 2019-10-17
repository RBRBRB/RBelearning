<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crouse extends Model
{
  //Table name
  protected $table = "crouses";
  //Primary Key
  public $primaryKey = 'subject_id';
  //Timestamps
  public $timeStamps = false;
  //
  public function chapters()
  {
    //return $this->hasMany('App\Chapter','subject_id');
    // return $this->belongsTo('App\Crouse', 'foreign_key');
    // return $this->belongsTo('App\Crouse', 'foreign_key', 'other_key');
  }
}
