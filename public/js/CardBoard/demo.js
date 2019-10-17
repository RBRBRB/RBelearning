var startpinchdist;  //record two fingers position at touchstart
var movepinchdist; //record two fingers position at touchmove
var distdiff = 0; //record diff of movepinchdist and startpinchdist

$("html").on("touchstart" , function(event){
  event.preventDefault();

  if(event.touches.length > 1)
  {
    startpinchdist = Math.hypot(
    event.touches[0].pageX - event.touches[1].pageX,
    event.touches[0].pageY - event.touches[1].pageY);

  }else if(event.touches.length == 1){
    //console.log("1 hand start in html")

  }
})


$("html").on("touchmove" , function(event){
  event.preventDefault();

  if(event.touches.length > 1){

    movepinchdist = Math.hypot(
    event.touches[0].pageX - event.touches[1].pageX,
    event.touches[0].pageY - event.touches[1].pageY);
    //console.log(movepinchdist - startpinchdist);

    if(movepinchdist - startpinchdist < -170)
    {
      //Zoom out gesture
      $('#demo').fadeOut(300);
      $('#overview').fadeIn(300);
    }else if(distdiff < movepinchdist - startpinchdist){
      //Zoom in gesture
    }else if(distdiff == 0){

    }

    //distdiff = movepinchdist - startpinchdist;

  }else {
    //console.log("1 hand move in html")
  }

})
$("html").on("touchend" , function(event){
  startpinchdist = 0;
  movepinchdist = 0;
})
    var timeout;    //time interval for card flipping
    var startPos = {};   //record the start position in filmStrip div
    var movePos = {};  //touch data while moving in card div
    var startPosFast = {};  //record the start position in card div
    var endPos = {};   //record the end position in filmStrip div
    var endPostFast = {};  //record the end position in filmStrip div
    var scrollDirection;   //use for fast strip sliding
    var touch;   //record touch node info
    var touchFast;


    $('#filmStrip').on('touchstart' , function(event){
      if(event.touches.length == 1){

      $('#filmStrip').fadeTo("slow" , 0.8);
      touch = event.touches[0];
      startPos = {x:touch.pageX,y:touch.pageY}
      scrollDirection = 0;
      }
    })

    $('.card').on('touchstart' , function(event){
      event.preventDefault();
      if(event.touches.length == 1){

        $('#filmStrip').fadeTo("slow" , 0.15);

        touchFast = event.touches[0];
        startPosFast = {x:touchFast.pageX,y:touchFast.pageY}
        scrollDirectionFast = 0;

        $this = $(this); //very important
        if($(this).find('.back').length == 1)
        {
          timeout = setTimeout(function() {
            $this.toggleClass('flipped');

            }, 600);
        }
      }else if(event.touches.length > 1)
      {

      }
    });


    $('#filmStrip').on('touchmove' , function(event){

      if(event.touches.length == 1){

      touch = event.touches[0];
      endPos = {x:touch.pageX,y:touch.pageY}
      //Determine sliding direction , Right for 1, Left for 1
      scrollDirection = touch.pageX-startPos.x > 0 ? 1 : -1;

      if(scrollDirection == -1)
      {
          $('.owl-carousel').trigger('next.owl.carousel' , [10]);
      }
      else if(scrollDirection == 1){
         $('.owl-carousel').trigger('prev.owl.carousel', [10]);
      }else {

      }

    }});

    $('.card').on('touchmove' , function(event){
      movePos = {x:event.touches[0].pageX,y:event.touches[0].pageY}
      var d = Math.hypot(movePos.x - startPosFast.x , movePos.y - startPosFast.y)
      if(d >= 60)
      {
        //prevent flipping judgment
        clearTimeout(timeout);
      }
    });

    $('.card').on('touchend' , function(){

      clearTimeout(timeout);
    });

    $("#filmStrip").on('touchend' , function(){

    })

//select n-th page in overview div and jump to responding slide in overview div
$('.item').click(function(){
  var index = $('.item').index(this)
  $('.owl-carousel').trigger('to.owl.carousel', [index, 0])
  $('#demo').removeAttr('style')
  $('#overview').css('display' , 'none')
  //alert(index)
})

// mark the current page while making a fist gesture [trigger enter key]
var record_mark_content_id = new Array()

var re_mark = $('.mark-star').closest('.cardbox')
$.each(re_mark , function(key , value){

  record_mark_content_id.push(value.getAttribute('content_id'))
})

//console.log(record_mark_content_id)

$(document).keypress(function(event){
  var keycode = (event.keyCode ? event.keyCode : event.which)
  var pageDOM = event.target
  if(keycode == '13')
  {
    var active_page_dom = pageDOM.getElementsByClassName('owl-item active')
    var mark_content_id = active_page_dom[0].children[0].getAttribute("content_id")
    var mark_star = getClosest(active_page_dom[0] , '.fa-star')

    var latest = $(this).data("time") || 0;
    var now = +new Date();
    var diff = now - latest;
    // prevent marking the page too frequently
    if(diff > 600)
    {
      $(mark_star).toggleClass('mark-star')

      if(record_mark_content_id.includes(mark_content_id))
      {
        record_mark_content_id = $.grep(record_mark_content_id , function(element){
          return element != mark_content_id
        })
      }else {
        record_mark_content_id.push(mark_content_id)
      }
      console.log(record_mark_content_id)

    }
    $(this).data("time",now);

  }

})

var getClosest = function (elem, selector) {
	for ( ; elem && elem !== document; elem = elem.children[0] ) {
		if ( elem.matches( selector ) ) return elem;
	}
	return null;
};


window.onbeforeunload = function (e) {
  if(record_mark_content_id.length > 0)
  {
      record_mark_contents()
  }
  //return 'Your own message goes here...';
}


function record_mark_contents(){
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  $.ajax({
    '_token':'{{csrf_token()}}',
    url: "/feedback",
    type: "post",
    data: {
      'record': record_mark_content_id,
    } ,
    success: function(result){
      console.log(result['info'])

  }});
}

//adjust overview image size , font-size , video width
$('.box span').css('font-size' , '10pt')
$(".box>p>img").css("width","100%");
$(".box>p>iframe").css("width","100%");
