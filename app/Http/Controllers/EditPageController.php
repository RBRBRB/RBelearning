<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Crouse;
use App\Chapter;
use App\Content;
use Auth;

class EditPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user()->name;
        $crouses = Crouse::where('owner' , $user)->get();
        $chapters = Chapter::where('owner' , $user)->get();
        $contents = Content::where('owner' , $user)->get();

        return view('CardBoard.edit', compact('crouses' , 'chapters' ,'contents' , 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $owner = Auth::user()->name;
        $input = $request->all();
        if($input['chaptername'] == "null")
        {
          //save crouse name
          $inputcrouse = $input['crousename'];
          $crousefilter = Crouse::where('subject' , $inputcrouse)->get();
          if($crousefilter->isEmpty())
          {
            $crouses = new Crouse();
            $crouses->subject = $inputcrouse;
            $crouses->owner = $owner;
            $crouses->save();
            return response()->json(['info' => 'crousecreate']);
          }
          else {
            return response()->json(['info' => 'failcreatecrouse']);
          }
        }else {
          // search crouse name to save chapter name
          $inputcrouse = $input['crousename'];
          $inputchapter = $input['chaptername'];
          $chapterfilter = Chapter::where('parent_crouse' , $inputcrouse)->where('chapter' , $inputchapter)->get();
          if($chapterfilter->isEmpty())
          {
            $chapters = new Chapter();
            $chapters->parent_crouse = $inputcrouse;
            $chapters->chapter = $inputchapter;
            $chapters->owner = $owner;
            $chapters->save();
            return response()->json(['info' => 'chaptercreate']);
          }else {
            return response()->json(['info' => 'failcreatechapter']);
          }

        }

        //return response()->json(['info' => $input['chaptername']]);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        //$owner = Auth::user()->name;
        $input = $request->all();
        $crouse = $input['crousename'];
        $chapter = $input['chaptername'];

        $contents = Content::where('crouse' , $crouse)->where('chapter' , $chapter)->get();
        return response()->json(['info' => $contents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $input = $request->all();
        $inputcrouse = $input['crousename'];
        $inputchapter = $input['chaptername'];
        $inputmp = $input['ftmp'];

        if($inputchapter == "null")
        {
          //update crouse folder name
          $crouse_index = Crouse::where('subject' , $inputmp)->get('subject_id');
          $rname_crosue = Crouse::find($crouse_index[0]['subject_id']);
          $rname_crosue->subject = $inputcrouse;
          $rname_crosue->save();

          $chapters_index = Chapter::where('parent_crouse' , $inputmp)->get('chapter_id');
          foreach ($chapters_index as $key => $chapter_index) {
            $rnamechapter = Chapter::find($chapter_index['chapter_id']);
            $rnamechapter->parent_crouse = $inputcrouse;
            $rnamechapter->save();
          }

          $contents_index = Content::where('crouse' , $inputmp)->get('content_id');
          foreach ($contents_index as $key => $content_index) {
            $rnamecontent = Content::find($content_index['content_id']);
            $rnamecontent->crouse = $inputcrouse;
            $rnamecontent->save();
          }

        }else {

          // update chapter folder name
          $chapters_index = Chapter::where('parent_crouse' , $inputcrouse)->where('chapter' , $inputmp)->get('chapter_id');

          foreach ($chapters_index as $key => $chapter_index) {
            $rnamechapter = Chapter::find($chapter_index['chapter_id']);
            $rnamechapter->chapter = $inputchapter;
            $rnamechapter->save();
          }


          $contents_index = Content::where('crouse' , $inputcrouse)->where('chapter' , $inputmp)->get('content_id');
          foreach ($contents_index as $key => $content_index) {
            $rnamecontent = Content::find($content_index['content_id']);
            $rnamecontent->chapter = $inputchapter;
            $rnamecontent->save();
          }

        }
        return response()->json(['info' => $input]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $input = $request->all();
        $inputcrouse = $input['crousename'];
        $inputchapter = $input['chaptername'];

        if($inputchapter == "null"){
          //remove crouses
          Crouse::where('subject' , $inputcrouse)->delete();
          return response()->json(['info' => 'crouseremove']);
        }else {
          // remove chapter
          Chapter::where('parent_crouse' , $inputcrouse)->where('chapter' , $inputchapter)->delete();
          return response()->json(['info' => 'chapteremove']);
        }
    }
}
