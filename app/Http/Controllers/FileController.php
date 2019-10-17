<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Content;
use Auth;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //$msg = $request->input('data');
        //return response()->json(['info' => $msg]);

        //$request->file('file')->move($dir, $filename);
        /*$validator = Validator::make($request->all(),
            [
                'file' => 'image',
            ],
            [
                'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
            ]);
        if ($validator->fails())
            return array(
                'fail' => true,
                'errors' => $validator->errors()
            );*/
        /*
        $extension = $request->file('file')->getClientOriginalExtension();
        $dir = public_path()."/uploads/";
        $filename = uniqid() . '_' . time() . '.' . $extension;
        //Storage::put('file.jpg', $request->file('file'));
        $request->file('file')->move(public_path()."/uploads/", $filename);
        */
        $owner = Auth::user()->name;
        $input = $request->all();
        $inputcrouse = $input['crosue'];
        $inputchapter = $input['chapter'];
        $front_html = $input['frontcontent'];
        $detail_html = $input['detailcontent'];

        $meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'; //!!important

        $dom = new \DOMDocument();
        $content = new Content();
        $content->crouse = $inputcrouse;
        $content->chapter = $inputchapter;

        $dom->loadHTML($meta . $front_html);
        $images = $dom->getElementsByTagName('img');

        if(!empty($images[0]))
        {
          foreach($images as $img){

              $data = $img->getAttribute('src');

              list($type, $data) = explode(';', $data);

              list(, $data)      = explode(',', $data);

              $data = base64_decode($data);

              $image_name= "/uploads/" . $img->getAttribute('data-filename');

              $path = public_path() . $image_name;

              file_put_contents($path, $data);

              $img->removeAttribute('src');

              $img->setAttribute('src', $image_name);

              $check_width = $this->getStyleValue($img->getAttribute('style') , 'width');

              if($check_width >= 1350)
              {
                $img->removeAttribute('style');
                $img->setAttribute('style', $img->getAttribute('style') . 'width:100%;');
              }
          }
          $front_html = $dom->saveHTML();
        }
        $content->front = $front_html;

        if(strlen($detail_html) != 11)
        {
          $dom = new \DomDocument();
          $dom->loadHTML($meta . $detail_html);
          $images = $dom->getElementsByTagName('img');
          if(!empty($images[0]))
          {
            foreach($images as $img){
                $data = $img->getAttribute('src');
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                $image_name= "/uploads/" . $img->getAttribute('data-filename');
                $path = public_path() . $image_name;
                file_put_contents($path, $data);
                $img->removeAttribute('src');
                $img->setAttribute('src', $image_name);

                $check_width = $this->getStyleValue($img->getAttribute('style') , 'width');
                if($check_width >= 1350)
                {
                  $img->removeAttribute('style');
                  $img->setAttribute('style', $img->getAttribute('style') . 'width:100%;');
                }
            }
            $detail_html = $dom->saveHTML();
          }
          $content->detail = $detail_html;
        }
        $content->owner = $owner;
        $content->save();

        return response()->json(['info' => 'addContentSuccess']);
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
        $id = trim($id , '{}');
        $input = $request->all();
        $front_html = $input['front_html'];
        $back_html = $input['back_html'];
        $update_content = Content::find($id);


        $meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'; //!!important

        $dom = new \DOMDocument();

        $dom->loadHTML($meta . $front_html);
        $images = $dom->getElementsByTagName('img');

        if(!empty($images[0]))
        {
          foreach($images as $img){

              $data = $img->getAttribute('src');

              list($type, $data) = explode(';', $data);

              if($data != null)
              {
                list(, $data)      = explode(',', $data);

                $data = base64_decode($data);

                $image_name= "/uploads/" . $img->getAttribute('data-filename');

                $path = public_path() . $image_name;

                file_put_contents($path, $data);

                $img->removeAttribute('src');

                $img->setAttribute('src', $image_name);
              }

              $check_width = $this->getStyleValue($img->getAttribute('style') , 'width');

              if($check_width >= 1350)
              {
                $img->removeAttribute('style');
                $img->setAttribute('style', $img->getAttribute('style') . 'width:100%;');
              }

          }
          $front_html = $dom->saveHTML();
        }
        $update_content->front = $front_html;


        if($back_html != "null")
        {
          $dom = new \DomDocument();
          $dom->loadHTML($meta . $back_html);
          $images = $dom->getElementsByTagName('img');
          if(!empty($images[0]))
          {
            foreach($images as $img){
                $data = $img->getAttribute('src');
                list($type, $data) = explode(';', $data);
                if($data != null)
                {
                  list(, $data)      = explode(',', $data);
                  $data = base64_decode($data);
                  $image_name= "/uploads/" . $img->getAttribute('data-filename');
                  $path = public_path() . $image_name;
                  file_put_contents($path, $data);
                  $img->removeAttribute('src');
                  $img->setAttribute('src', $image_name);
                }

                $check_width = $this->getStyleValue($img->getAttribute('style') , 'width');
                if($check_width >= 1350)
                {
                  $img->removeAttribute('style');
                  $img->setAttribute('style', $img->getAttribute('style') . 'width:100%;');
                }
            }
            $back_html = $dom->saveHTML();
          }
          $update_content->detail = $back_html;
        }

        $update_content->save();

        return response()->json(['info' => 'Content Update Success...']);
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
        $id = trim($id , '{}');
        Content::destroy($id);
        return response()->json(['info' => 'OK']);
    }

    public function getStyleValue($style , $type)
    {
      $value = false;

      $all = explode(';' , $style);
      foreach($all as $index => $part)
      {
        $temp = explode(':' , $part);
        if(trim($temp[0] == $type))
        {
          $value = (int)trim($temp[1]);
        }
      }
      return $value;
    }

    public function setStyleValue($tag , $value)
    {
      $tag->removeAttribute('style');
      $tag->setAttribute('style', $tag->getAttribute('style') . $value);

      return $tag;
    }


}
