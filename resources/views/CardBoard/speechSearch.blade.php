<link rel="stylesheet" href="{{secure_asset('css/CardBoard/speechSearch.css')}}">


<div class="speechIcon">
  <i class="fa fa-search" id = "speech_search" style=" font-size: 36px"></i>
</div>

<div class="row">
  <div class="col-lg search-overlay">
    <div id="speech_box">
      <div class="microphone-icon">
        <i class="fa fa-microphone" aria-hidden="true" style="font-size: 45px ;padding-top:9px"></i>
        <span class="ripple"></span>
      </div>
      <div class="speech2text">
        <h2 id="speech2text">Listening...</h2>
      </div>


    </div>
  </div>
</div>

<script src="{{secure_asset('js/CardBoard/speechSearch.js')}}"></script>
