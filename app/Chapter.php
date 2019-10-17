<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
  //Table name
  protected $table = "chapters";
  //Primary Key
  public $primaryKey = 'chapter_id';
  //Timestamps
  public $timeStamps = false;

  public function crouse()
  {
    //return $this->belongsTo('App\Crouse','subject_id');
    // return $this->belongsTo('App\Crouse', 'foreign_key');
    // return $this->belongsTo('App\Crouse', 'foreign_key', 'other_key');
  }
  public function content()
  {
    //return $this->hasMany('App\Content','chapter_id');
  }
}
