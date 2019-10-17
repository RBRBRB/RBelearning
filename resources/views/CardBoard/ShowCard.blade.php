<ol>
  <li>
    <!-- Fisrt Layer Subject Card Group Example
    <div class="card card--group">Card Group1</div>
    -->
    @if(count($order_record) > 0)
      @foreach($order_record as $id => $record)
        <div class="card card--group">
          {{$record[0]->created_at}}
        </div>
        <ol>
          @foreach($record as $j => $r)
            <li>
              <div class="card chapterCard" value="{{$r->mark_contents}}">
                {{$r->course}} -
                {{$r->chapter}}
              </div>
            </li>
          @endforeach
        </ol>



      @endforeach
    @endif


  </li>
</ol>

<form id="content_record" class="" action="/demo/" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="recordArg" value="" id="record_content_value">
</form>

<script type="text/javascript">
$(".chapterCard").on('click' , function(){

  $('#record_content_value').val($(this).attr('value'));
  //alert("ready to send chapter id");
  document.getElementById("content_record").submit();

});
</script>
<script src="{{secure_asset('js/CardBoard/CardGroup.js')}}"></script>
<!--
<ol>
   <li><div class="card is-loading">Loading card...</div></li>
   <li><div class="card is-loading">Loading card...</div></li>
   <li><div class="card is-loading">Loading card...</div></li>
</ol>
-->
