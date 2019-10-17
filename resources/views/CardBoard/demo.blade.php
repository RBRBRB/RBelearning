<!DOCTYPE html>
<html>
  <head>

    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    @include('CardBoard.fullnav')
    @include('CardBoard.speechSearch')

     <link rel="stylesheet" href="{{secure_asset('css/CardBoard/demo.css')}}">
     <link rel="stylesheet" href="{{secure_asset('css/CardBoard/flip.css')}}">
     <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" rel="stylesheet">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  </head>

  <body>

    <div id="demo" style="display: none;">
    <div class="owl-carousel owl-theme" id="owl">
      @foreach($contents as $content)
      <div class="cardbox" content_id = '{!!$content->content_id!!}'>
        <div class="card">
          @if(count($content->detail) > 0)
            <div class="tip-box">
              <?php
                $str_id = strval($content->content_id);
                $check_mark = (strpos($mark_id , $str_id ))  ? "yes" : "no";
              ?>
              @if($check_mark == "yes")
                <i class="fa fa-star mark-star" style=" font-size: 24px"></i>
              @else
                <i class="fa fa-star" style=" font-size: 24px"></i>
              @endif
                <i class="fa fa-info" style=" font-size: 24px"></i>
            </div>
            <div class="front">{!!$content->front!!}</div>
            <div class="back">{!!$content->detail!!}</div>
          @else
            <div class="tip-box">
              <?php
                $str_id = strval($content->content_id);
                $check_mark = (strpos($mark_id , $str_id ))  ? "yes" : "no";
              ?>
              @if($check_mark == "yes")
                <i class="fa fa-star mark-star" style=" font-size: 24px"></i>
              @else
                <i class="fa fa-star" style=" font-size: 24px"></i>
              @endif

            </div>
            <div class="front">{!!$content->front!!}</div>
          @endif
        </div>
      </div>
      @endforeach
    </div>

    <div id="filmStrip">
      <ul id="filmnav"></ul>
    </div>
  </div>

  <div id="overview" style="display: none;">
    <ul class="grid">
      @foreach($contents as $index => $content)
        <li class="item">
          <div class="box">
            {!!$content->front!!}
          </div>
        </li>
      @endforeach
    </ul>
  </div>

  <div id="overview_search" >
    <ul class="grid"></ul>
  </div>

  </body>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.owl-carousel').owlCarousel({
          items:1,
          margin:10,
          autoHeight:true

      });
  });

  </script>
  <script src="{{secure_asset('js/CardBoard/demo.js')}}"></script>
</html>
