
<link rel="stylesheet" href="{{secure_asset('css/CardBoard/showOwlCard.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script type="text/javascript">
    var chapters = {!! json_encode($chapters->toArray()) !!};
</script>

<div id="crouse_pointer"> Select Course </div>
<span class="arrow arrow-bottom"></span>
<p></p>
<div class="owl-carousel owl-theme" id="owlcrouse">
  @foreach($crouses as $index_x=>$crouse)
    <div class="owlcards owlcrouse_item">
      {{$crouse->subject}}
    </div>
  @endforeach
</div>
<div id="crouse_pointer"> Select Chapter </div>
<span class="arrow arrow-bottom"></span>
<p></p>
<div class="owl-carousel owl-theme" id="owlchapter">
</div>
<form id="reviewContent" class="" action="/demo/" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="pathArg" value="" id="chapterPathSet">
</form>

<script type="text/javascript">
  var owlcrouse = $('#owlcrouse')
  var owlchapter = $('#owlchapter')
  var cur_crouse
  var cur_chapter_id = -1

  owlchapter.owlCarousel({
      items:3,
      center:true,
      margin:15,
      nav: false,
      callbacks: true,
  });

  owlcrouse.owlCarousel({
      items:3,
      center:true,
      margin:15,
      nav: false,
      loop: true,
      callbacks: true,
      onInitialized: onCrouseInitialized,
  });


  function onCrouseInitialized(event){
    var pageDOM = event.target
    var crouse_dom = pageDOM.getElementsByClassName('owl-item active')
    cur_crouse = crouse_dom[1].textContent.trim()

    var cur_chapters
    cur_chapters = chapters.filter(function(chapter){
      return chapter.parent_crouse == cur_crouse
    })

    if(cur_chapters.length > 0)
    {
      for (var i = 0; i < cur_chapters.length; i++) {
        owlchapter.trigger('add.owl.carousel', ['<div class="owlcards owlchapter_item" id='+cur_chapters[i].chapter_id+'>' + cur_chapters[i].chapter+ '</div>']);
      }
      owlchapter.trigger('refresh.owl.carousel');
    }
  }

  owlcrouse.on('translate.owl.carousel' , function(event){
    //reset cur_chapter_id
    cur_chapter_id = -1
  })

  owlcrouse.on('translated.owl.carousel' , function(event){

    var pageDOM = event.target
    var crouse_dom = pageDOM.getElementsByClassName('owl-item active')
    //var active_item_count = content_dom.length
    //var cur_owlitem_id = (active_item_count - 1) / 2
    cur_crouse = crouse_dom[1].textContent.trim()
    var cur_chapters
    cur_chapters = chapters.filter(function(chapter){
      return chapter.parent_crouse == cur_crouse
    })

    var old_chapter_cnt = $('.owlchapter_item').length
    for (var i = 0; i < old_chapter_cnt; i++) {
      owlchapter.trigger('remove.owl.carousel', i).trigger('refresh.owl.carousel')
    }

    if(cur_chapters.length > 0)
    {
      for (var i = 0; i < cur_chapters.length; i++) {
        owlchapter.trigger('add.owl.carousel', ['<div class="owlcards owlchapter_item" id='+cur_chapters[i].chapter_id+'>' + cur_chapters[i].chapter+ '</div>']);

      }

      owlchapter.trigger('refresh.owl.carousel');
    }
  })

  owlchapter.on('translated.owl.carousel' , function(event){

    var chapter_content = event.target.getElementsByClassName('owlchapter_item')
    var ch_item_index = event.item.index

    cur_chapter_id = chapter_content[ch_item_index].id
    //console.log(cur_chapter_id)

  })

  $('body').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
      if(keycode == '13'){
          if(cur_chapter_id == -1)
          {
            var chapter_content = event.target.getElementsByClassName('owlchapter_item')
            if(chapter_content.length != 0)
            {
              cur_chapter_id = chapter_content[0].id
            }
          }

          if(cur_chapter_id != -1){
            $('#chapterPathSet').val(cur_chapter_id);
            //console.log(cur_chapter_id)
            document.getElementById("reviewContent").submit();
          }
      }
  });

  $(".chapterCard").on('click' , function(){
    //alert($(this).attr('value'));

    $('#chapterPathSet').val($(this).attr('value'));
    //alert("ready to send chapter id");
    document.getElementById("reviewContent").submit();

  });
</script>
