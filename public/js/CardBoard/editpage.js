
window.onload = function(){

  //editable carousel when edit mode on
  var owl = $('#owlinedit')
  var record_ftmp // the old folder name while updating
  var content_id
  var cur_owlitem_id;
  owl.owlCarousel({
      items:1,
      margin:10,
      nav: false,
      callbacks: true,
  });

  //write the carousel stage content to summernote when a translated has finished
  owl.on('translated.owl.carousel', function(event) {
    cur_owlitem_id = event.item.index
    var pageDOM = event.target
    var content_dom = pageDOM.getElementsByClassName('owl-item active')
    content_id = content_dom[0].children[0].id

    //find the specific content page
    var editcontent = $.grep(contents , function(content , index){
      return content.content_id == content_id
    })

    $('#front_note').summernote('code' , editcontent[0].front) //remember to remove div class="number"
    $('#back_note').summernote('code' , editcontent[0].detail)
  })

  $(document).on('click','.folder',function(e){
      e.preventDefault()
      var latest = $(this).data("time") || 0;
      var now = +new Date();
      var diff = now - latest;
      if(diff >520 && diff < 1800)
      {
        var rename_folder = $('.update_folder_name')
        //record_ftmp = $(this).text().trim()
        if(rename_folder.length == 0)
        {
          $(this).find('p').css("display" , "none")
          $(this).append("<input type='text' 'name='' class='update_folder_name' size='9' autofocus>")
        }else if(rename_folder.length > 0)
        {
          rename_folder.prev().removeAttr("style")
          rename_folder.remove()
          $(this).find('p').css("display" , "none")
          $(this).append("<input type='text' 'name='' class='update_folder_name' size='9' autofocus>")
          $('input[autofocus]:visible:first').focus();
        }
      }
      $(this).data("time",now);

      $('.update_folder_name').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which)
        if(keycode == '13')
        {
          if($(this).val() == "")
          {
            //enter the empty string
            $(this).prev().removeAttr("style")
            $(this).remove()
          }else {
            //update the folder name
            record_ftmp = $(this).prev().text()

            if($(this).parent().hasClass('crouse_dir'))
            {
              //update crouse_dir name
              updatefolder2db($(this).val() , "null" , record_ftmp)
              $('.window').eq($(this).parent().index() - $('.crouse_dir').length ).find('h2').text($(this).val())


            }else if ($(this).parent().hasClass('chapter_dir')) {
              //ipdate chapter_dir name
              updatefolder2db($(this).parent().prev().text() , $(this).val() , record_ftmp)
            }

            $(this).prev().removeAttr("style").text($(this).val())
            $(this).remove()
          }

        }
      })
  });


  $("div").off("dblclick" , ".folder").on("dblclick",".folder",function(e) {
    e.stopPropagation();
    var index;
    if($(this).hasClass('dul'))
    {
      index = $(this).index() / 2; // because the .on duplicate trgger problem
    }else {
      index = $(this).index();
    }

    $('.folder').each(function() {
      $(this).removeClass('open');
    });

    $('.window').each(function() {
      $(this).removeClass('open');
    });

    //prevent unfold when create
    if(!$(this).hasClass('create_crouse_folder'))
      $(this).addClass('open');

    if($(this).hasClass('crouse_dir icon-r'))
    { //remove crouse folder
      //remove the crouse folder if it is empty
      var crousename
      var chaptername
      if(!$('.window').eq(index).find('div').hasClass('chapter_dir'))
      {
        crousename = $(this).text().trim()

        var info = rmfolder2db(crousename , "null")

        if(info == 'crouseremove')
        {
          $('.window').eq(index).remove()
          $(this).remove()
          contents = contents.filter(function(content){
            return content.crouse != crousename
          })
        }else {
          //something wrong happened ...
        }
      }
    }else if ($(this).hasClass('chapter_dir icon-r')) {
      //remove chapter folder
      chaptername = $(this).text().trim()
      crousename = $('.window').eq($(this).parent().index() - $('.crouse_dir').length ).find('h2').text()

      var info = rmfolder2db(crousename , chaptername)

      if(info == 'chapteremove')
      {
        $(this).remove()
        contents = contents.filter(function(content){
          return ((content.crouse != crousename) && (content.chapter != chaptername))
        })
      }else {
        //something wrong happened
      }
    }else if ($(this).hasClass('chapter_dir icon-e')) {
      chaptername = $(this).text().trim()
      crousename = $('.window').eq($(this).parent().index() - $('.crouse_dir').length ).find('h2').text()

      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
      $.ajax({
        '_token':'{{csrf_token()}}',
        url: "/editpage/edit",
        type: "post",

        data: {
          'crousename': crousename,
          'chaptername': chaptername,
        } ,
        success: function(result){
          info = result['info']

          for(var i = 0 ; i <= $('.owl-item').length ; i++)
          { owl.trigger('remove.owl.carousel', i).trigger('refresh.owl.carousel')} //or [i]
          info.forEach(function(element){

            owl.trigger('add.owl.carousel', ['<div id='+ element.content_id +'>'+ element.front +'</div>']);
            owl.trigger('refresh.owl.carousel');
          })
      }});
    }

    //record the path
    if(!$(this).hasClass('chapter_dir'))
    {
      $('.window').eq(index).addClass('open');
      if($('li.breadcrumb-item').length > 0)
      {
        $('li.breadcrumb-item:nth-child(3)').remove();
        $('li.breadcrumb-item:nth-child(2)').remove();

        $('.breadcrumb').append("<li class='breadcrumb-item' >" + $(this).text() + "</li>");
      }
    }else{
      $('.breadcrumb').append("<li class='breadcrumb-item' >" + $(this).text() + "</li>");
    }

  });

  $(document).on("click",".window > h2",function() {
    $(this).parent().removeClass('open');
    $('.folder').removeClass('open');
  });

  //Dynamically detect front_html content to enable/disable submit button and back_html
  $('#front_note').on('summernote.change', function(we, contents, $editable) {
    if($(this).summernote('isEmpty'))
    {
      $('#back_note').summernote('disable');
      $('#summer_submit').prop("disabled", true);
    }else {
      $('#back_note').summernote('enable');
      $('#summer_submit').prop("disabled", false);
    }
  });

  //Send user custom contents to db
  $('#summer_submit').click(function(){

    var front_html = $('#front_note').summernote('code')
    var back_html = $('#back_note').summernote('code')
    var inputcrouse = $('li.breadcrumb-item:nth-child(2)').text().trim()
    var inputchapter = $('li.breadcrumb-item:nth-child(3)').text().trim()

    //alert(front_html)
    if(inputcrouse == "" || inputchapter =="")
    {
      alert("Please check storage path...")
    }else {

      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
      $.ajax({
        '_token':'{{csrf_token()}}',
        url: "/uploadfile",
        type: "post",
        data: {
          'crosue': inputcrouse,
          'chapter':inputchapter,
          'frontcontent': front_html,
          'detailcontent': back_html,
        } ,
        success: function(result){
          alert(result['info']);
          $('#front_note').summernote('reset');
          $('#back_note').summernote('reset');

          var curid = contents.slice(-1)[0].content_id + 1
          var newcontent = {"content_id": curid , "front":front_html , "detail":back_html , "crouse":inputcrouse, "chapter":inputchapter}

          contents.push(newcontent)
          //console.log(contents)
      }});
    }
  })

  //This section describes the functionalities in toolBar
  //create folder
  $(document).on('click','.fa-plus',function(e){
    $(this).removeClass('fa-plus').addClass('fa-check')
    if($('#delete').find('i').hasClass('fa-check'))
      $('#delete').click()
    if(!$("div").hasClass('create_crouse_folder'))
    {
      //chapter_dul = chapter_dul + 1
      $(".container").append("<div class='folder icon-2 crouse_dir create_crouse_folder dul'></div>");
      $(".window").append("<div class='folder icon-2 chapter_dir create_chapter_folder dul'></div>");
    }
  })

  $(document).on('click','#create>.fa-check',function(e){
    $('#create').find('i').removeClass('fa-check').addClass('fa-plus')
    $('.create_crouse_folder').remove()
    $('.create_chapter_folder').remove()
  })

  //name crouse folder
  $("div").on('click','.create_crouse_folder',function(e){

    $("#myModal").attr('style' , 'display: block') //show backshadow
    $('.folderinput').attr('name' , 'create_crouse_folder')
    $('input[autofocus]:visible:first').focus();

  });

  //name chapter folder
  var crouseIndex
  var chapter_dul = 0;
  $("div").on('click','.create_chapter_folder',function(e){

    $("#myModal").attr('style' , 'display: block') //show backshadow
    $('.folderinput').attr('name' , 'create_chapter_folder')
    $('input[autofocus]:visible:first').focus();
    crouseIndex = $(this).parent().index() - $('.crouse_dir').length + 1;
  });

  // remove folder
  $(document).on('click','#delete',function(e){
    $("#delete").find('i').toggleClass('fa-trash-o fa-check')
    if($('#create').find('i').hasClass('fa-check'))
      $('#create>.fa-check').click();
    $('.folder').each(function() {
      $(this).toggleClass('icon-l icon-r')
    });
  })



  $("body").keypress(function(event){

    var keycode = (event.keyCode ? event.keyCode : event.which)
    var dirname = $('.folderinput').val()
    var result
      if(keycode == '13' && dirname != ''){

        if($('.folderinput').attr('name') == 'create_crouse_folder')
        {
          result = createfolder2db(dirname , "null");
          if(result == 'crousecreate')
          {
            $(".create_crouse_folder").text(dirname)

            $(".create_crouse_folder").removeClass('icon-2').addClass('icon-l') //replace '+' to 'L'

            $(".create_crouse_folder").removeClass('create_crouse_folder')

            $('.create_chapter_folder').remove()

            $('.container').append('<div class="window draggable"><h2>' + dirname + '</h2></div>')

            $('.managebar').load('http://yogoeasy.me/editpage/ .managebar')
          }else {

            $('.create_crouse_folder').remove()
            $('.create_chapter_folder').remove()
            alert("The folder has already exist ...")
          }

        }else if ($('.folderinput').attr('name') == 'create_chapter_folder') {

          var specified_create_ch = $('.window').eq(crouseIndex).find(".create_chapter_folder")
          var parent_dir_name = $('.window').eq(crouseIndex).find("h2").text()

          result = createfolder2db(parent_dir_name , dirname)
          if(result == 'chaptercreate')
          {
            specified_create_ch.text(dirname)

            specified_create_ch.removeClass('icon-2').addClass('icon-l')

            specified_create_ch.removeClass('create_chapter_folder')

            $('.create_chapter_folder').remove()

            $('.create_crouse_folder').remove()

            $('.managebar').load('http://yogoeasy.me/editpage/ .managebar')
          }else {

          }
        }

        $('.folderinput').val('')
        $("#myModal").attr('style' , 'display: none')


      }
  });
  //create folder
  function createfolder2db(crouse_name , chapter_name){
    var info;
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
      '_token':'{{csrf_token()}}',
      url: "/editpage",
      type: "post",
      async: false,
      data: {
        'crousename': crouse_name,
        'chaptername': chapter_name,
      } ,
      success: function(result){
        info = result['info']

    }});
    return info
  }
  //remove folder
  function rmfolder2db(crouse_name , chapter_name){
    var info;
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
      '_token':'{{csrf_token()}}',
      url: "/editpage/rm",
      type: "post",
      async: false,
      data: {
        'crousename': crouse_name,
        'chaptername': chapter_name,
      } ,
      success: function(result){
        info = result['info']
    }});
    return info
  }
  //rename folder
  function updatefolder2db(crouse_name , chapter_name , ftmp){

    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
      '_token':'{{csrf_token()}}',
      url: "/editpage/update",
      type: "post",
      async: false,
      data: {
        'crousename': crouse_name,
        'chaptername': chapter_name,
        'ftmp': ftmp,
      } ,
      success: function(result){
        $('.managebar').load('http://yogoeasy.me/editpage/ .managebar')
    }});

  }

  //delete contents
  function rmcontent2db(content_id){
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
      '_token':'{{csrf_token()}}',
      url: "/uploadfile/{" + content_id + "}",
      type: "delete",
      async: false,
      success: function(result){
        //Reset summernote
        $('#back_note').summernote('reset');
        $('#front_note').summernote('reset');
        //Remove the deleted stage in the preview_in_editmode div
        owl.trigger('remove.owl.carousel' , cur_owlitem_id).trigger('refresh.owl.carousel')
        //update the contents
        contents = contents.filter(function(content){
          return content.content_id != content_id
        })
      }
    });
  }

  //update contents
  function updatecontent2db(front_html , back_html , content_id){
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
      '_token':'{{csrf_token()}}',
      url: "/uploadfile/{" + content_id + "}",
      type: "put",
      async: false,
      data: {
        'front_html': front_html,
        'back_html': back_html,
      } ,
      success: function(result){
        alert(result['info'])
        //Update the current stage in the preview_in_editmode div
        $('#'+content_id).empty().html(front_html)
        //Update the contents
        $.each(contents , function(index , content){
          if(content.content_id == content_id)
          { content.front = front_html }
        })
      }
    });
  }

  //edit content
  $(document).on('click','#edit',function(e){

    if($('.chapter_dir').hasClass('icon-e'))
    {
      $('#front_note').summernote('reset');
      $('#back_note').summernote('reset');

      for(var j = 0 ; j < $('.owl-item').length ; j++)
      {
        owl.trigger('remove.owl.carousel', j).trigger('refresh.owl.carousel')
      }

      $('#summer_update , #summer_delete , #summer_prev , #summer_next').css('display' , 'none')

      $('#summer_submit').removeAttr("style")

    }else {
      $('#summer_submit').css('display' , 'none')
      $('#summer_update , #summer_delete , #summer_prev , #summer_next').removeAttr("style")

    }

    $("#edit").find('i').toggleClass('fa-edit fa-check')
    $('.chapter_dir').each(function(){
      $(this).toggleClass('icon-l icon-e')
    })
  })

  $('#summer_prev').on('click' , function(){
      owl.trigger('prev.owl.carousel')
  })
  $('#summer_next').on('click' , function(){
    owl.trigger('next.owl.carousel')
  })

  $('#summer_update').on('click' , function(){
    var front_html = $('#front_note').summernote('code')
    var back_html = $('#back_note').summernote('code')
    if($('#back_note').summernote('isEmpty'))
    {
      updatecontent2db(front_html , "null" , content_id)
    }else {
      updatecontent2db(front_html , back_html , content_id)
    }
  })

  $('#summer_delete').on('click' , function(){
    rmcontent2db(content_id)
  })

  // When the user clicks anywhere outside of the modal, close it
  var modal = document.getElementById("myModal")
  var body = document.body
  window.onclick = function(event) {

    if (event.target == modal) {
      modal.style.display = "none"
      $('.folderinput').val('')
    }else if (event.target == body) {

      $('.update_folder_name').prev().removeAttr("style")
      $('.update_folder_name').remove()

      $('.window').removeClass('open')
      $('.folder').removeClass('open')

    }


    //console.log(event.target);
  }

  $('#file').click(function(){
    console.log(user)
  })

}
