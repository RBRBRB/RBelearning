<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title></title>

    <link rel="stylesheet" href="{{secure_asset('css/CardBoard/editpage.css')}}">


    @include('CardBoard.fullnav')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
    <script src="{{secure_asset('js/CardBoard/editpage.js')}}"></script>
    <script type="text/javascript">
      var contents = {!! json_encode($contents->toArray()) !!};
      var user = {!!json_encode($user)!!};
    </script>


  </head>
  <body>
    <!-- Log bar  -->
    <div id="logbar">
      <dropdown>
        <input id="toggle1" type="checkbox">
        <label for="toggle1" class="animate">{{ Auth::user()->name }}<i class="fa fa-user float-right"></i></label>
        <ul class="animate">
          <li class="animate">Analysis<i class="fa fa-code float-right"></i></li>
          <li class="animate">Setting<i class="fa fa-cog float-right"></i></li>
          <li class="animate"><a href="{{ route('logout') }}"
             onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout
          </a><i class="fa fa-sign-out float-right"></i></li>
        </ul>
      </dropdown>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>

      <!--
      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          {{ Auth::user()->name }}
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
      </div>-->
    </div>
    <!--toggle the container and tool bar-->
    <div class="managebar">

      <div class="modal" id="myModal">
        <div class="modal-content">
          <span> + Press enter to create folder</span>
          <input type="text" name="" value="" class="folderinput" autofocus >
        </div>
      </div>
      <!-- The area of folders which provides choosing, creating, deleting -->
      <div class="container pre-scrollable">
        <!-- List crouse folders -->
        @if(count($crouses) > 0)
          @foreach($crouses as $index => $crouse)
            <div class="folder icon-l crouse_dir">
              <p>{{$crouse->subject}}</p>
            </div>
          @endforeach
        @else
          <p> No Crouse Now !</p>
        @endif
        <!-- List chapter folders -->
        @foreach($crouses as $index => $crouse)
          <div class="window draggable">
            <h2>{{$crouse->subject}}</h2>
            <?php $chapter_chunk = $chapters->where('parent_crouse' , $crouse->subject); ?>
              @foreach($chapter_chunk as $chapter)
                <div class="folder icon-l chapter_dir"><p>{{$chapter->chapter}}</p></div>
              @endforeach
          </div>
        @endforeach
      </div>
      <!-- List four functionalities in toolbar-->
      <div id="toolBar">
        <button id="search">
          <i class="fa fa-search" style=" font-size: 42px"></i>
        </button>
        <button id="delete">
          <i class="fa fa-trash-o" style=" font-size: 42px"></i>
        </button>
        <button id="create">
          <i class="fa fa-plus" style=" font-size: 42px"></i>
        </button>
        <button id="edit">
          <i class="fa fa-edit" style=" font-size: 42px"></i>
        </button>
      </div>
      <!-- Show the current folder path-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home"></i></li>
      </ol>


    </div>
    <!-- Show the contents preview when edit mode on -->
    <div id="preview_in_editmode">
      <div class="owl-carousel owl-theme" id="owlinedit">
      </div>
    </div>
    <!--file uploading or content edition-->
    <nav id="editor" style="display: none;">
      <div id="creation" class="editor_item">Creation</div>
      |
      <div id="file" class="editor_item">Files</div>
    </nav>
    <div id="edit_block">
      <div id="front_note"></div>
      <div id="back_note"></div>
      <button type="button" class="btn btn-outline-primary" id="summer_prev" style="display:none">Prev</button>
      <button type="submit" class="btn btn-primary" id="summer_submit" disabled>Create</button>
      <button type="submit" class="btn btn-outline-info" id="summer_update" style="display:none">Update</button>
      <button type="submit" class="btn btn-danger" id="summer_delete" style="display:none">Delete</button>
      <button type="button" class="btn btn-outline-primary" id="summer_next" style="display:none">Next</button>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <!-- Initialize Quill editor -->
    <script>
      $(document).ready(function() {
          $('#front_note').summernote({
            height: 250,
          });

          $('#back_note').summernote({
            height: 200,
          });

          $('#back_note').summernote('disable');
      });
    </script>

  </body>
</html>
