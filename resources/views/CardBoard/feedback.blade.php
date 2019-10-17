<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html" charset="utf-8" />

		<meta name="viewport" content="width=device-width, height=device-height,initial-scale=1.0, shrink-to-fit=no">
		<meta name="description" content="" >
    <script type="text/javascript">
      var chapters = {!!json_encode($chapters->toArray())!!};

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>

	</head>
	<body>
		<div id="content-container" class="haha">

			 @include('CardBoard.fullnav')

			    <div class="jumbotron pre-scrollable">
						<div class="container">
							@include('CardBoard.ShowCard')
						</div>
					</div>
					<div class="jumbotron pre-scrollable d3-block" style="padding-top:0px;">
						<div class="container" id="d3-block">
							<div class="select">
							  <select name="slct" id="course_slct">
							    <option selected disabled>Choose a course</option>
									@foreach($courses as $k => $course)
										<option value="{{$k}}">{{$course->subject}}</option>
									@endforeach

							  </select>
							</div>

							<div class="select">
								<select name="slct" id="chapter_slct">
									<option selected disabled>Choose a chapter</option>
								</select>
							</div>

							<div class="select">
								<select name="slct" id="time_slct">
									<option selected disabled>No time limited</option>
									<option value="1">1 day ago</option>
									<option value="7">1 week ago</option>
									<option value="30">1 month ago</option>
									<option value="365">1 year ago</option>
								</select>
							</div>

						</div>
						<div id="analysis_block">
							<div id="graph_block">
							</div>
							<div id="browse_content">
							</div>
						</div>

					</div>
		</div>
		<script src="{{secure_asset('js/CardBoard/feedback_d3.js')}}"></script>
	</body>
</html>
