<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;
use App\Chapter;
use App\User;
use App\Record;
use Auth;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $owner = Auth::user()->name;
        $is_admin = User::where('name' , $owner)->first();
        $contentid = 1;
        $contents = Content::where('content_id' , $contentid)->get();
        return view('CardBoard.demo' , compact('contents' , 'is_admin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        //
        $input = $request->all();
        $text = $input['msg'];
        $allContent = Content::pluck('front' ,'content_id');

        $search_target = array();
        foreach ($allContent as $content_id => $content) {

          if (strpos($content , $text) !== false) {
              array_push($search_target , $content_id);
          }
        }

        $related_content = Content::whereIn('content_id' , $search_target)->get(['content_id' , 'front' , 'detail']);

        return response()->json(['info'=>$related_content]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $prev_url = $request->session()->get('_previous')['url'];

        $owner = Auth::user()->name;
        $is_admin = User::where('name' , $owner)->first();

        if($prev_url == "https://yogoeasy.me/feedback")
        {
          // request from the feedback page
          // show only marked contents
          $recontent = json_decode($request->input('recordArg'));
          $contents = Content::whereIn('content_id' , $recontent)->get();

        }else if ($prev_url == "https://yogoeasy.me/cardboard") {
          // request from the cardboard page
          // show the entire contents accroding to the chapter
          $chapterPathId = $request->input('pathArg');
          $chapterfilter = Chapter::where('chapter_id' , $chapterPathId)->get();
          $crouse = $chapterfilter[0]['parent_crouse'];
          $chapter = $chapterfilter[0]['chapter'];

          if($is_admin->is_admin() == 1)
          {
            $latest_record = Record::where('owner' , $owner)
                            ->where('course' , $crouse)
                            ->where('chapter' , $chapter)->latest()->first();

            $mark_id = $latest_record->mark_contents;

          }else {
            // code...
          }

          $contents = Content::where('crouse' , $crouse)->where('chapter' , $chapter)->get();
        }

        return view('CardBoard.demo' , compact('contents' , 'is_admin' , 'mark_id'));


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
