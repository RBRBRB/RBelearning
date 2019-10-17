<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Crouse;
use App\Chapter;
use App\Content;
use App\User;
use Auth;

class CrouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $owner = Auth::user()->name;
        $is_admin = User::where('name' , $owner)->first();
        //dd($is_admin->is_admin());
        if($is_admin->is_admin())
        {
          //teacher
          $crouses = Crouse::where('owner' , $owner)->get();
          $chapters = Chapter::where('owner' , $owner)->get();
          return view('CardBoard.homeweb' , compact('crouses' , 'chapters' , 'is_admin'));
        }else {
          //student
          $crouses = Crouse::all();
          $chapters = Chapter::all();
          return view('CardBoard.homeweb' , compact('crouses' , 'chapters' , 'is_admin'));
        }


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
        //
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
