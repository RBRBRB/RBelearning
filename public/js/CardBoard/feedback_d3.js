
var margin = {top: 40, right: 10, bottom: 0, left: 40},
    width = 900 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom


var x = d3.scale.linear()
    .range([0 ,width]);

var barHeight = 25;

var color = d3.scale.ordinal()
    .range(["#003D73", "#B5C1B4"]);

var duration = 750,
    delay = 25

var partition = d3.layout.partition()
   .value(function(d) { return d.size; });

var xAxis = d3.svg.axis()
   .scale(x)
   .orient("top")
   .tickFormat(d3.format("d"))



var svg



$(document).ready(function(){
  $('#course_slct').on('change' , function(){
    var selected_chapter = []
    var selected_couse = $('#course_slct :selected').text()


    selected_chapter = $.grep(chapters , function(value , key){
      return value.parent_crouse == selected_couse
    })

    //console.log(selected_chapter)
    $('#chapter_slct option').remove()
    $('#chapter_slct').append($('<option selected disabled></option>').text('Choose a chapter'))

    $.each(selected_chapter , function(index , value){
      $('#chapter_slct').append($('<option></option>').attr('value' , index).text(value.chapter))
    })
  })

  //send course and chapter to backend for filtering
  $('#chapter_slct').on('change' , function(){

    d3.select("svg").remove()
    filter_remarks($('#course_slct :selected').text() , $('#chapter_slct :selected').text())
  })

  $('#time_slct').on('change' , function(){
    var course_slct_option = $('#course_slct :selected').text(),
        chapter_slct_option = $('#chapter_slct :selected').text()
    if(course_slct_option != 'Choose a course' &&  chapter_slct_option != 'Choose a chapter')
    {
      d3.select("svg").remove()
      filter_remarks_by_time(course_slct_option , chapter_slct_option , $('#time_slct :selected').val())
    }
  })

  //If the user hover the mouse in the content_id(in graph_block) , the content will be displayed in the browsw_block
  $(".d3-block").on('mouseover','.content_id_text' , function(e){

    var reg = new RegExp('^\\d+$')  //check content_id or student name

    var slct_content_id = e.target.textContent //get the content_id

    if(reg.test(slct_content_id))
      browse_content(slct_content_id) //to query the content
  })


})


function filter_remarks(course , chapter){
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  $.ajax({
    '_token':'{{csrf_token()}}',
    url: "/feedback/filter",
    type: "post",
    data: {
      'course': course,
      'chapter': chapter,
    } ,
    success: function(result){
      if(result['info'] == 'OK')
      {
        //load json and generate the graph
        loadJson();
      }
  }});
}

function filter_remarks_by_time(course , chapter , time){
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  $.ajax({
    '_token':'{{csrf_token()}}',
    url: "/feedback/filter",
    type: "post",
    data: {
      'course': course,
      'chapter': chapter,
      'time': time
    } ,
    success: function(result){
      //console.log(result['info'])

      if(result['info'] == 'OK')
      {
        //load json and generate the graph
        loadJson();
      }
  }});
}

function browse_content(content_id)
{
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  $.ajax({
    '_token':'{{csrf_token()}}',
    url: "/feedback/browse",
    type: "post",
    data: {
      'content_id': content_id,
    } ,
    success: function(result){
      //append the content
      var browse_content = result['info'][0]['front']
      $('#browse_content').empty()
      $('#browse_content').append(browse_content);
  }});
}

function loadJson(){
  //load json and generate the graph
  svg = d3.select("#graph_block").append("svg")
  .attr("width", width + margin.left + margin.right)
  .attr("height", height + margin.top + margin.bottom)
  .append("g")
  .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  svg.append("rect")
      .attr("class", "background")
      .attr("width", width)
      .attr("height", height)
      .on("click", up);

  svg.append("g")
      .attr("class", "x axis")
     .append("text")
      .attr("x" , width-30)
      .attr("y" , -22)
      .style("font-size", "14px")
      .text("Total")

  svg.append("g")
      .attr("class", "y axis")
      .append("text")
       .attr("x" , -height + 15)
       .attr("transform","rotate(-90)")
       .style("font-size", "14px")
       .text("Content_id")



  d3.json("/uploads/record.json", function(error, root) {
    if (error) throw error;
    //console.log(root)
    partition.nodes(root);
    x.domain([0 , root.value]).nice();
    down(root, 0);
  });
}


function down(d, i) {
  if (!d.children || this.__transition__) return;
  var end = duration + d.children.length * delay;

  // Mark any currently-displayed bars as exiting.
  var exit = svg.selectAll(".enter")
      .attr("class", "exit");

  // Entering nodes immediately obscure the clicked-on bar, so hide it.
  exit.selectAll("rect").filter(function(p) { return p === d; })
      .style("fill-opacity", 1e-6);

  // Enter the new bars for the clicked-on data.
  // Per above, entering bars are immediately visible.
  var enter = bar(d)
      .attr("transform", stack(i))
      .style("opacity", 1);

  // Have the text fade-in, even though the bars are visible.
  // Color the bars as parents; they will fade to children if appropriate.
  enter.select("text").style("fill-opacity", 1e-6);
  enter.select("rect").style("fill", color(true));

  // Update the x-scale domain.
  x.domain([0, d3.max(d.children, function(d) { return d.value; })]).nice();

  // Update the x-axis.
  svg.selectAll(".x.axis").transition()
      .duration(duration)
      .call(xAxis);

  // Transition entering bars to their new position.
  var enterTransition = enter.transition()
      .duration(duration)
      .delay(function(d, i) { return i * delay; })
      .attr("transform", function(d, i) { return "translate(0," + barHeight * i * 1.2 + ")"; });

  // Transition entering text.
  enterTransition.select("text")
      .style("fill-opacity", 1);

  // Transition entering rects to the new x-scale.
  enterTransition.select("rect")
      .attr("width", function(d) { return x(d.value); })
      .style("fill", function(d) { return color(!!d.children); });

  // Transition exiting bars to fade out.
  var exitTransition = exit.transition()
      .duration(duration)
      .style("opacity", 1e-6)
      .remove();

  // Transition exiting bars to the new x-scale.
  exitTransition.selectAll("rect")
      .attr("width", function(d) { return x(d.value); });

  // Rebind the current node to the background.
  svg.select(".background")
      .datum(d)
    .transition()
      .duration(end);

  d.index = i;
}

function up(d) {
  if (!d.parent || this.__transition__) return;
  var end = duration + d.children.length * delay;

  // Mark any currently-displayed bars as exiting.
  var exit = svg.selectAll(".enter")
      .attr("class", "exit");

  // Enter the new bars for the clicked-on data's parent.
  var enter = bar(d.parent)
      .attr("transform", function(d, i) { return "translate(0," + barHeight * i * 1.2 + ")"; })
      .style("opacity", 1e-6);

  // Color the bars as appropriate.
  // Exiting nodes will obscure the parent bar, so hide it.
  enter.select("rect")
      .style("fill", function(d) { return color(!!d.children); })
    .filter(function(p) { return p === d; })
      .style("fill-opacity", 1e-6);

  // Update the x-scale domain.
  x.domain([0, d3.max(d.parent.children, function(d) { return d.value; })]).nice();

  // Update the x-axis.
  svg.selectAll(".x.axis").transition()
      .duration(duration)
      .call(xAxis);

  // Transition entering bars to fade in over the full duration.
  var enterTransition = enter.transition()
      .duration(end)
      .style("opacity", 1);

  // Transition entering rects to the new x-scale.
  // When the entering parent rect is done, make it visible!
  enterTransition.select("rect")
      .attr("width", function(d) { return x(d.value); })
      .each("end", function(p) { if (p === d) d3.select(this).style("fill-opacity", null); });

  // Transition exiting bars to the parent's position.
  var exitTransition = exit.selectAll("g").transition()
      .duration(duration)
      .delay(function(d, i) { return i * delay; })
      .attr("transform", stack(d.index));

  // Transition exiting text to fade out.
  exitTransition.select("text")
      .style("fill-opacity", 1e-6);

  // Transition exiting rects to the new scale and fade to parent color.
  exitTransition.select("rect")
      .attr("width", function(d) { return x(d.value); })
      .style("fill", color(true));

  // Remove exiting nodes when the last child has finished transitioning.
  exit.transition()
      .duration(end)
      .remove();

  // Rebind the current parent to the background.
  svg.select(".background")
      .datum(d.parent)
    .transition()
      .duration(end);
}

// Creates a set of bars for the given data node, at the specified index.
function bar(d) {
  var bar = svg.insert("g", ".y.axis")
      .attr("class", "enter")
      .attr("transform", "translate(0,5)")
    .selectAll("g")
      .data(d.children)
    .enter().append("g")
      .style("cursor", function(d) { return !d.children ? null : "pointer"; })
      .on("click", down);

  bar.append("text")
      .attr("class" , "content_id_text")
      .attr("x", -6)
      .attr("y", barHeight / 2)
      .attr("dy", ".35em")
      .style("text-anchor", "end")
      .text(function(d) { return d.name; });

  bar.append("rect")
      .attr("width", function(d) { return x(d.value); })
      .attr("height", barHeight);

  return bar;
}

// A stateful closure for stacking bars horizontally.
function stack(i) {
  var x0 = 0;
  return function(d) {
    var tx = "translate(" + x0 + "," + barHeight * i * 1.2 + ")";
    x0 += x(d.value);
    return tx;
  };
}
