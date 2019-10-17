<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
  //Table name
  protected $table = "contents";
  //Primary Key
  public $primaryKey = 'content_id';
  //Timestamps
  public $timeStamps = false;

  public function chapter()
  {
    //return $this->belongsTo('App\Chapter','chapter_id');
    // return $this->belongsTo('App\Crouse', 'foreign_key');
    // return $this->belongsTo('App\Crouse', 'foreign_key', 'other_key');
  }
  public function get_course_name()
  {
    return $this->crouse;
  }
  public function get_chapter_name()
  {
    return $this->chapter;
  }
}
