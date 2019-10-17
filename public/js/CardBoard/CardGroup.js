var cards = document.querySelectorAll('.card--group')

Array.prototype.forEach.call(cards, function(card, i){

    card.addEventListener('click', classToggle);
});

function classToggle() {
    this.classList.toggle('is-active');

  console.log(this);
}
$('body').keypress(function(event){
  var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        //Roll up all chapter if press enter
        $('.card--group').removeClass('is-active');
    }


});

$('.card--group').each(function(index){
  //var _index = index;
  $(this).on('click' , function(){
    //alert(index);
  })

});
