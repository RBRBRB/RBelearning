<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Crouse;
use App\Chapter;

class CustomLoginController extends Controller
{
    //
    public function login(Request $request){

      $intended_url = $request->session()->get('url')['intended'];
      $email = $request->email;
      $user = User::where('email' , $email)->first();

      Auth::login($user); //login to Auth

      $owner = Auth::user()->name;
      $crouses = Crouse::where('owner' , $owner)->get();
      $chapters = Chapter::where('owner' , $owner)->get();

      return redirect()->route('cardboard.index');

    }
}
