<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Record;
use App\Chapter;
use App\User;
use App\Content;
use App\Crouse;
use Carbon\Carbon;
class FeedbackController extends Controller
{
   /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
    public function index(){
      $owner = Auth::user()->name;
      $is_admin = User::where('name' , $owner)->first();

      if($is_admin == 1){
        // teacher view
        $records = Record::where('owner' , $owner)->get();

        $order_record = $records->groupBy(function($date){
          return Carbon::parse($date->created_at)->day;
        });

        $courses = Crouse::where('owner' , $owner)->get();
        $chapters = Chapter::where('owner' , $owner)->get();

        //$now = Carbon::now();

        //dd($order_record);
        return view('CardBoard.feedback' , compact('order_record' , 'is_admin' , 'courses' , 'chapters'));
      }else {
        //student view
        $records = Record::where('owner' , $owner)->get();

        $order_record = $records->groupBy(function($date){
          return Carbon::parse($date->created_at)->day;
        });

      }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function browse(Request $request){
      $input = $request->all();

      $content = Content::where('content_id' , $input)->get('front');

      return response()->json(['info' => $content]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
      $input = $request->all();

      $content_tmp = Content::where('content_id' , $input['record'][0])->first();  //get the first content by first content_id in the record array
      $record = json_encode($input['record']);

      $owner = Auth::user()->name;
      if(count($record) > 0)
      {
        $records = new Record();
        $records->mark_contents = $record;
        $records->owner = $owner;
        $records->course = $content_tmp['crouse'];
        $records->chapter = $content_tmp['chapter'];
        $records->save();
      }

      return response()->json(['info' => 'Record finished...']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function filter(Request $request){
       $input = $request->all();
       $course = $input['course'];
       $chapter = $input['chapter'];

       if($request->has('time'))
       {
         $date = new Carbon;

         $date->subDays($input['time']);

         $specified_records = Record::where('created_at', '>=', $date->toDateTimeString() )->where('course' , $course)->where('chapter' , $chapter)->get(['owner','mark_contents']);

         $records = $specified_records->map(function($item , $key){
           return [$item['owner'] => $this->spiltString2Arr($item['mark_contents'])];
         });

         $this->convert_record2json($records);

         return response()->json(['info'=>'OK']);

       }else {
         // No time limited

         $specified_records = Record::where('course' , $course)->where('chapter' , $chapter)->get(['owner','mark_contents']);

         $records = $specified_records->map(function($item , $key){
           return [$item['owner'] => $this->spiltString2Arr($item['mark_contents'])];
         });

         $this->convert_record2json($records);

         return response()->json(['info'=>"OK"]);
       }
     }

     public function spiltString2Arr($string)
     {
       $temp = substr($string , 2,-2);
       $array = explode('", "', $temp);
       return $array;
     }

     function array_flatten($array,$return) {
    	for($x = 0; $x <= count($array); $x++) {
    		if(is_array($array[$x])) {
    			$return = array_flatten($array[$x], $return);
    		}
    		else {
    			if(isset($array[$x])) {
    				$return[] = $array[$x];
    			}
    		}
    	}
    	return $return;
    }

    public function convert_record2json($records)
    {
      $content_id_count_arr = array();

      foreach ($records as $key => $record) {

        if(isset($content_id_count_arr[key($record)]))
        {
          // The key has exist
          // push the current record to existed array by key
          array_push($content_id_count_arr[key($record)] , current($record));

        }else {
          // The key doesn't exist
          $content_id_count_arr[key($record)] = array_values($record);
        }
      }

      $filter_arr = array(); //紀錄每位使用者的content_id(包含重複)，以一維array呈現
      $mark_content_arr = array();  //存放該章節底下所記錄的content_id array
      foreach ($content_id_count_arr as $key => $count_arr) {

        $flatten_arr = array();

          foreach($count_arr as $i)
          {
            foreach($i as $j)
            {
              $flatten_arr[] = $j;

            }
            $mark_content_arr = array_merge($mark_content_arr , $i);
          }

          $filter_arr[$key] = $flatten_arr;
      }


       $reformat_arr = array(); //key為content_id, value為{name:count}的集合

         foreach ($filter_arr as $name => $record_arr) {
           // "RBLin" : {{"11"},{"12","21"},{"13"}}

           foreach ($record_arr as $key => $record_id) {
             //{0:"12" , 1:"21"}

             if(isset($reformat_arr[$record_id]))
             {
               foreach ($reformat_arr[$record_id] as $yo =>$value) {

                 if($name == $yo)
                 {
                   $reformat_arr[$record_id][$yo] = current($reformat_arr[$record_id]) + 1;
                 }else {
                   $reformat_arr[$record_id] =  $reformat_arr[$record_id] + array($name => 1);
                 }
               }

             }else {
               $reformat_arr[$record_id] = array($name => 1);
             }
           }
         }
         //fill the array corresponding to the d3 json format
         $josn_array = array("name"=>"d3_analysis" , "children"=>array());

         $cnt = 0;
         foreach ($reformat_arr as $content_id => $record_arr) {

           array_push($josn_array['children'] , array("name"=>"$content_id" , "children"=>array()));

           foreach ($record_arr as $name => $size) {
             //
             array_push($josn_array['children'][$cnt]['children'] , array("name"=>$name , "size"=>$size));
           }
           $cnt = $cnt + 1;


         }

         $test_json_data = json_encode($josn_array);
         $json_path = public_path() . "/uploads/record.json";
         file_put_contents($json_path , $test_json_data);

    }

}
