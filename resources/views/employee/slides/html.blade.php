<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--css-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/iframe/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/iframe/css/simplebar.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('assets/iframe/css/slide-styles.css')}}">
</head>
<body >
	<div class="content-wrapper" data-simplebar>
		<section class="full-height white-card step-card step-card--first">
            @if(!empty($slide->video_url))
                <div class="card-video">
                    <iframe src="{{ $slide->video_url }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
                </div>
            @elseif($slide->image)
			<div class="card-image">
				<img src="{{$slide->imagePreviewUrl}}" class="img-fluid" alt="{{$slide->name}}" >
			</div>
            @endif
			{!! $slide->content !!}
		</section>
	</div>
	<!--js-->
	<script src="{{asset('assets/iframe/js/jquery-2.2.4.js')}}"></script>
	<script src="{{asset('assets/iframe/js/popper.min.js')}}"></script>
	<script src="{{asset('assets/iframe/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/iframe/js/simplebar.js')}}"></script>
</body>
</html>
