<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Record extends Model
{
  //Table name
  protected $table = "record";
  //Primary Key
  public $primaryKey = 'feedback_id';
  //Timestamps
  public $timeStamps = false;
  /*
  public function setCreatedAtAttribute($date)
  {
    $this->attributes['created_at'] = Carbon::createFromFormat('Y-m-d' , $date);
  }*/


}
