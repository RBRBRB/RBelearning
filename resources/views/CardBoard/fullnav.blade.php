
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <link rel="stylesheet" href="{{secure_asset('css/CardBoard/fullnav.css')}}">
    <link rel="stylesheet" href="{{secure_asset('css/CardBoard/CardGrouping.css')}}">


<!--    Bootstrap cdn-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/paper/bootstrap.min.css" />



<!-- icons-->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!--font awsome -->
  <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">

<!--    Jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

<!--    Greensock-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/TweenMax.min.js"></script>

<!-- Custon js -->

</head>


   <div class="wrapper">
        <div class="menu-btn">
            <button type="button"><i class="material-icons">menu</i></button>
        </div>

        <div class="menu">
            <div class="row">
                <div class="col-lg overlay">
                    <div class="nav">
                        <ul id="fullnav">
                            @if($is_admin->admin == 1)
                              <li><a href="/cardboard/">Home</a></li>
                              <li><a href="/demo/">Demo</a></li>
                              <li><a href="/editpage/">Edit</a></li>
                              <li><a href="/feedback">Feedback</a></li>
                            @else
                              <li><a href="/cardboard/">Home</a></li>
                              <li><a href="/demo/">Demo</a></li>
                              <li><a href="/feedback">Feedback</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

  <script>

      var t1 = new TimelineMax({paused: true});

      t1.to(".overlay", 0.8, {

          top: 0,
          ease: Expo.easeInOut

      });

      t1.staggerFrom(".menu ul li", 1, {y: 100, opacity: 0, ease: Expo.easeOut}, 0.1);

      t1.reverse();
      $(".menu-btn").mouseenter(function(){
        $('html,body').animate({ scrollTop: 0 }, 500);
        t1.reversed(!t1.reversed());
      })

  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
