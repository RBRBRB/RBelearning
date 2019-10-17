var search_overlay = new TimelineMax({paused: true});
var start_recognitoin = false
var recognition

search_overlay.to(".search-overlay", 0.6, {
    top: 0,
    ease: Expo.easeInOut

});

search_overlay.staggerFrom(".search-overlay", 1, {y: 100, opacity: 0, ease: Expo.easeOut}, 0.1);

search_overlay.reverse();
$(".speechIcon").mouseenter(function(){
  $('html,body').animate({ scrollTop: 0 }, 500);
  search_overlay.reversed(!search_overlay.reversed());

  if (!('webkitSpeechRecognition' in window)) {
    alert("No speech recognition ...")
  } else {
    if(start_recognitoin == false)
    {
      start_recognitoin = !start_recognitoin;
      recognition = new webkitSpeechRecognition();

      recognition.continuous = true //
      recognition.interimResults = false
      recognition.lang = "cmn-Hant-TW";

      recognition.onstart = function(){
        console.log('start recognition...');
      };


      recognition.onresult = function(event){
        var res = event.results
        var res_text = res[event.resultIndex][0].transcript
        console.log(res_text);
        document.getElementById("speech2text").textContent = res_text

        if(res_text != "Listening...")
        {
          //$('.microphone-icon').mouseenter();
          search_content(res_text);
        }

      };

      recognition.start();

    }else {
      start_recognitoin = !start_recognitoin;

      recognition.onend = function(){
        console.log('stop recognition...');
        document.getElementById("speech2text").textContent = "Listening...";
      };
      recognition.stop();
    }
  }
})

$('.microphone-icon').mouseenter(function(){
  start_recognitoin = !start_recognitoin;
  recognition.onend = function(){
    console.log('stop recognition...');
    document.getElementById("speech2text").textContent = "Listening...";
  };

  recognition.stop();
  search_overlay.reversed(!search_overlay.reversed());

})

$('#speech2text').on('change' , function(){

  console.log("yoyoyoyoy");
})



function search_content(string){
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  $.ajax({
    '_token':'{{csrf_token()}}',
    url: "/demo/search",
    type: "post",
    data: {
      'msg': string,
    } ,
    success: function(result){
      //console.log(result['info'])
      var search_res = result['info']

      search_res.forEach(function(element){
        //console.log(element.front)

        $("#overview_search>ul").append("<li class='item'><div class='box'>" + element.front + "</div></li>")
      })




  }});
}
